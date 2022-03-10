<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Http;
use Illuminate\Console\Command;
use App\Models\BankCurrencyInfo;
use HeadlessChromium\{
    Browser\ProcessAwareBrowser,
    BrowserFactory,
    Page
};
use DiDom\Document;

class ParserCurrencyRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:currency:rates {--type=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private array $overallInfo = [];

    private ProcessAwareBrowser $browser;

    private string $type;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $availableTypes = ['request', 'browser'];
        $type = $this->option('type');

        if ($type === null || !in_array($type, $availableTypes)) {
            echo 'You need to call command like php artisan parse:currency:rates --type=request/browser'.PHP_EOL;
        } else {
            $this->type = $type;

            if ($this->type === 'request') {
                $this->parseMainPageWithRequest();
            } else {
                $this->parseMainPageWithBrowser();
            }

            $this->update();
        }

        return 0;
    }

    public function update(): void
    {
        var_dump($this->overallInfo, json_encode($this->overallInfo));

        foreach ($this->overallInfo as $bankName => $departments) {
            BankCurrencyInfo::upsert(
                $departments,
                ['name'],
                [
                    'name',
                    'address',
                    'phones',
                    'website',
                    'working_time',
                    'coordinates',
                    'last_update',
                    'currency_info',
                    'bank_name'
                ]
            );
        }
    }

    private function getDataFromMainPage(string $html): void
    {
        $document = new Document($html);
        $now = date('Y-m-d H:i:s');

        $departments = $document->find('table');
        array_shift($departments);
        array_shift($departments);

        foreach ($departments as $department) {
            $bank = trim(str_replace('Отделения ', '', $department->find('thead')[0]->find('th')[0]->text()));

            $tbody = $department->find('tbody')[0];
            $this->overallInfo[$bank] = [];
            echo "bank {$bank}".PHP_EOL;

            $trs = $tbody->find('tr');

            foreach ($trs as $currentDepartment) {
                $tds = $currentDepartment->find('td');
                $departmentInfo = $tds[0]->find('div');
                $innerPageLink = 'https://myfin.by'.$departmentInfo[0]->find('a')[0]->href;
                $address = $departmentInfo[0]->text();

                $lastUpdate = trim($departmentInfo[1]->text());

                if ($now >= date('Y-m-d').' '.$lastUpdate.':00') {
                    $lastUpdate = date('Y-m-d').' '.$lastUpdate.':00';
                } else {
                    $lastUpdate = date('Y-m-d', strtotime('-1 day')).' '.$lastUpdate.':00';
                }

                if ($this->type === 'request') {
                    $innerInfo = $this->parseInnerPageWithRequest($innerPageLink);
                } else {
                    $innerInfo = $this->parseInnerPageWithBrowser($innerPageLink);
                }

                $bankBuysUsd = $tds[1]->text();
                $bankSellsUsd = $tds[2]->text();
                $bankBuysEur = $tds[3]->text();
                $bankSellsEur = $tds[4]->text();

                $this->overallInfo[$bank][] = array_merge($innerInfo, [
                    'last_update' => $lastUpdate,
                    'currency_info' => [
                        'usd' => [
                            'bank_buys' => $bankBuysUsd,
                            'bank_sells' => $bankSellsUsd
                        ],
                        'eur' => [
                            'bank_buys' => $bankBuysEur,
                            'bank_sells' => $bankSellsEur
                        ]
                    ]
                ]);

                echo "department with address {$address}".PHP_EOL;
                echo "last update {$lastUpdate}".PHP_EOL;
                echo "inner page link {$innerPageLink}".PHP_EOL;
                echo "pokupka usd {$bankBuysUsd}".PHP_EOL;
                echo "drodaja usd {$bankSellsUsd}".PHP_EOL;
                echo "pokupka eur {$bankBuysEur}".PHP_EOL;
                echo "pokupka eur {$bankSellsEur}".PHP_EOL;
            }

            $this->update();
            die();
        }
    }

    private function getDataFromInnerPage(string $html): array
    {
        $document = new Document($html);

        $info = $document->find('.table-responsive')[0]->find('table')[0];
        $fields = $info->find('tr');

        $name = $fields[0]->find('td')[1]->text();
        $address = $fields[1]->find('td')[1]->text();
        $phones = $this->splitPhones($fields[2]->find('td')[1]->text());
        $workingTime = implode(', ', array_map(fn (string $elem): string => trim($elem), explode(',', $fields[3]->find('td')[1]->text())));
        $website = trim($fields[4]->find('td')[1]->text());

        $indexOfCoordinates = strpos($html, 'coords');
        $start = strpos($html, '[', $indexOfCoordinates);
        $end = strpos($html, ']', $indexOfCoordinates);
        $coordinates = substr($html, $start + 1, $end - $start - 1);

        echo "nazvanie: {$name}".PHP_EOL;
        echo "adres: {$address}".PHP_EOL;
        $viewPhones = implode(',', $phones);
        echo "telefon: {$viewPhones} ".PHP_EOL;
        echo "vremya raboti: {$workingTime}".PHP_EOL;
        echo "website: {$website}".PHP_EOL;
        echo "coordinates: {$coordinates}".PHP_EOL;

        return [
            'name' => $name,
            'address' => $address,
            'phones' => $phones,
            'working_time' => $workingTime,
            'website' => $website,
            'coordinates' => array_map('trim', explode(',', $coordinates))
        ];
    }

    private function parseMainPageWithRequest(): void
    {
        $response = Http::get('https://myfin.by/currency/minsk');
        $this->getDataFromMainPage($response->body());
    }

    private function parseInnerPageWithRequest(string $url): array
    {
        sleep(1);
        $response = Http::get($url);
        return $this->getDataFromInnerPage($response->body());
    }

    private function parseMainPageWithBrowser(): void
    {
        $browserFactory = new BrowserFactory();

        // starts headless chrome
        $this->browser = $browserFactory->createBrowser([
            'headers' => [
                ':authority' => 'myfin.by',
                ':method' => 'POST',
                ':path' => '/currency/minsk',
                ':scheme' => 'https',
                'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
                'accept-encoding' => 'gzip, deflate, br',
                'accept-language' => 'ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
                'cache-control' => 'max-age=0',
                'content-length' => '3407',
                'content-type' => 'application/x-www-form-urlencoded',
                'cookie' => 'PHPSESSID=8s792snv9cqi3takpn5lqt3rc4; _ym_uid=1646767858171861499; _ym_d=1646767858; _ym_isad=2; _ym_visorc=b; _csrf=12ee7f5ebb38e50ccc21e81ec4165e1a54b9a3fc1eb1be80a199b50369cb198ea%3A2%3A%7Bi%3A0%3Bs%3A5%3A%22_csrf%22%3Bi%3A1%3Bs%3A32%3A%22sVCCwiYBiW86AWmWTac2j4t84I97N_Xk%22%3B%7D; cf_clearance=ll4Ny44hsU2V0mDABuJOvCymUspoaG7.mFyxSZTUvSk-1646767877-0-150; __cf_bm=.yImDbcfbAlksmcASY6pLorJmIGJj3O4sXdm_0vqEFc-1646767878-0-AZqBuJBnHmji329H5z9rYomUkjEH6b7y2+fgBMMyv2TrAfQnVBAvSdKEhv6zslD/irGVBT35acU3cSqKidf80OTbn9mFTMukLeQnKRvSxFs4dssW4bsXnTIDZ+m3nsfZmg==; push_close=3; __gads=ID=eb33789a8a078916-225dbde456cd00b5:T=1646767895:RT=1646767895:S=ALNI_MZxKiCTt_zrwb6n9962SYM3INZvMA; cf_chl_rc_ni=5; cf_chl_2=e50836e83575d5d; cf_chl_prog=x11',
                'origin' => 'https://myfin.by',
                'sec-ch-ua' => '" Not A;Brand";v="99", "Chromium";v="98", "Opera";v="84"',
                'sec-ch-ua-mobile' => '?0',
                'sec-ch-ua-platform' => "Windows",
                'sec-fetch-dest' => 'document',
                'sec-fetch-mode' => 'navigate',
                'sec-fetch-site' => 'same-origin',
                'upgrade-insecure-requests' => '1',
                'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.109 Safari/537.36 OPR/84.0.4316.31'
            ],
            'headless' => false,
            'windowSize' => [1920, 1000]
        ]);

        try {
            $page = $this->browser->createPage();
            $page->navigate('https://myfin.by/currency/minsk')->waitForNavigation();
            $html = $page->getHtml(5000);

            $this->getDataFromMainPage($html);
        } finally {
            // bye
            $this->browser->close();
        }
    }

    private function parseInnerPageWithBrowser(string $url): array
    {
        try {
            // creates a new page and navigate to an URL
            $page = $this->browser->createPage();
            $page->navigate($url)->waitForNavigation();
            $html = $page->getHtml(5000);

            return $this->getDataFromInnerPage($html);
        } finally {

        }
    }

    private function splitPhones(string $phones): array
    {
        return array_map(fn (string $elem): string => trim($elem), preg_split('/(;|,)/', $phones));
    }
}
