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
    protected $signature = 'parse:currency:rates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private array $overallInfo = [];

    private ProcessAwareBrowser $browser;

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
        // $this->parseMainPage();
        $this->browserParseMainPage();

        return 0;
    }

    private function browserParseMainPage(): void
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
                // 'referer' => 'https://myfin.by/currency/minsk?__cf_chl_tk=hWw6LZUnDi2QB3X1MNzBYdyvoj08jPj8D.wNr6pf23Y-1646767953-0-gaNycGzNCX0',
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
            // creates a new page and navigate to an URL
            $page = $this->browser->createPage();
            // $page->navigate('https://myfin.by/currency/minsk')->waitForNavigation(Page::DOM_CONTENT_LOADED, 5000);
            $page->navigate('https://myfin.by/currency/minsk')->waitForNavigation();
            $html = $page->getHtml(5000);

            // $document = new Document('C:\Users\Admin\Desktop\MYPROJECTS\best-currency-checker\server\public\main.html', true);
            $document = new Document($html);

            $departments = $document->find('table');
            array_shift($departments);
            array_shift($departments);
            // dd($departments[0]->text());

            foreach ($departments as $department) {
                $bank = trim(str_replace('Отделения ', '', $department->find('thead')[0]->find('th')[0]->text()));
                $tbody = $department->find('tbody')[0];
                $this->overallInfo[$bank] = [];
                echo "bank {$bank}".PHP_EOL;

                $trs = $tbody->find('tr');
                // array_shift($trs);

                foreach ($trs as $currentDepartment) {
                    $tds = $currentDepartment->find('td');
                    $departmentInfo = $tds[0]->find('div');
                    $innerPageLink = 'https://myfin.by'.$departmentInfo[0]->find('a')[0]->href;
                    $address = $departmentInfo[0]->text();
                    $lastUpdate = $departmentInfo[1]->text();

                    $innerInfo = $this->browserParseSubPage($innerPageLink);
                    // dd($innerInfo);
                    // die();

                    $bankBuysUsd = $tds[1]->text();
                    $bankSellsUsd = $tds[2]->text();
                    $bankBuysEur = $tds[3]->text();
                    $bankSellsEur = $tds[4]->text();

                    $this->overallInfo[$bank][] = array_merge($innerInfo, [
                        'department_last_update' => $lastUpdate,
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

                var_dump($this->overallInfo);
                die();
            }

            $this->browser->close();
            die();
        } finally {
            // bye
            $this->browser->close();
        }
    }

    private function browserParseSubPage(string $url): array
    {
        try {
            // creates a new page and navigate to an URL
            $page = $this->browser->createPage();
            $page->navigate($url)->waitForNavigation();
            $html = $page->getHtml(5000);

            $document = new Document($html);

            // $contents = file_get_contents('C:\Users\Admin\Desktop\MYPROJECTS\best-currency-checker\public\submain.html');
            // $document = new Document($contents);

            $info = $document->find('.table-responsive')[0]->find('table')[0];
            $fields = $info->find('tr');

            $name = $fields[0]->find('td')[1]->text();
            $address = $fields[1]->find('td')[1]->text();
            $phones = $fields[2]->find('td')[1]->text();
            $workingTime = implode(', ', array_map(fn (string $elem): string => trim($elem), explode(',', $fields[3]->find('td')[1]->text())));
            $website = trim($fields[4]->find('td')[1]->text());

            $indexOfCoordinates = strpos($html, 'coords');
            $start = strpos($html, '[', $indexOfCoordinates);
            $end = strpos($html, ']', $indexOfCoordinates);
            $coordinates = substr($html, $start + 1, $end - $start - 1);

            echo "nazvanie: {$name}".PHP_EOL;
            echo "adres: {$address}".PHP_EOL;
            echo "telefon: {$phones} ".PHP_EOL;
            echo "vremya raboti: {$workingTime}".PHP_EOL;
            echo "website: {$website}".PHP_EOL;
            echo "coordinates: {$coordinates}".PHP_EOL;

            return [
                'name' => $name,
                'address' => $address,
                'phones' => $phones,
                'working_time' => $workingTime,
                'website' => $website,
                'coordinates' => explode(',', $coordinates)
            ];
        } finally {

        }
    }



















    private function parseMainPage(): void
    {
        // $document = new Document('https://myfin.by/currency/minsk');
        $document = new Document('C:\Users\Admin\Desktop\MYPROJECTS\best-currency-checker\server\public\main.html', true);

        $departments = $document->find('table');
        array_shift($departments);
        array_shift($departments);
        // dd($departments[0]->text());

        foreach ($departments as $department) {
            $bank = trim(str_replace('Отделения ', '', $department->find('thead')[0]->find('th')[0]->text()));
            $tbody = $department->find('tbody')[0];
            $this->overallInfo[$bank] = [];
            echo "bank {$bank}".PHP_EOL;

            $trs = $tbody->find('tr');
            // array_shift($trs);

            foreach ($trs as $currentDepartment) {
                $tds = $currentDepartment->find('td');
                $departmentInfo = $tds[0]->find('div');
                $innerPageLink = 'https://myfin.by'.$departmentInfo[0]->find('a')[0]->href;
                $address = $departmentInfo[0]->text();
                $lastUpdate = $departmentInfo[1]->text();

                $innerInfo = $this->parseInnerPage($innerPageLink);
                dd($innerInfo);
                die();

                $bankBuysUsd = $tds[1]->text();
                $bankSellsUsd = $tds[2]->text();
                $bankBuysEur = $tds[3]->text();
                $bankSellsEur = $tds[4]->text();

                $this->overallInfo[$bank][] = array_merge($innerInfo, [
                    // 'department_address' => $address,
                    'department_last_update' => $lastUpdate,
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
                die();
            }
        }

        die();
    }

    private function parseInnerPage(string $url): array
    {
        $contents = Http::withHeaders([
            // ':authority' => 'myfin.by',
            // ':method' => 'POST',
            // ':path' => '/currency/minsk',
            // ':scheme' => 'https',
            'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'accept-encoding' => 'gzip, deflate, br',
            'accept-language' => 'ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
            'cache-control' => 'max-age=0',
            'content-length' => '3407',
            'content-type' => 'application/x-www-form-urlencoded',
            'cookie' => 'PHPSESSID=8s792snv9cqi3takpn5lqt3rc4; _ym_uid=1646767858171861499; _ym_d=1646767858; _ym_isad=2; _ym_visorc=b; _csrf=12ee7f5ebb38e50ccc21e81ec4165e1a54b9a3fc1eb1be80a199b50369cb198ea%3A2%3A%7Bi%3A0%3Bs%3A5%3A%22_csrf%22%3Bi%3A1%3Bs%3A32%3A%22sVCCwiYBiW86AWmWTac2j4t84I97N_Xk%22%3B%7D; cf_clearance=ll4Ny44hsU2V0mDABuJOvCymUspoaG7.mFyxSZTUvSk-1646767877-0-150; __cf_bm=.yImDbcfbAlksmcASY6pLorJmIGJj3O4sXdm_0vqEFc-1646767878-0-AZqBuJBnHmji329H5z9rYomUkjEH6b7y2+fgBMMyv2TrAfQnVBAvSdKEhv6zslD/irGVBT35acU3cSqKidf80OTbn9mFTMukLeQnKRvSxFs4dssW4bsXnTIDZ+m3nsfZmg==; push_close=3; __gads=ID=eb33789a8a078916-225dbde456cd00b5:T=1646767895:RT=1646767895:S=ALNI_MZxKiCTt_zrwb6n9962SYM3INZvMA; cf_chl_rc_ni=5; cf_chl_2=e50836e83575d5d; cf_chl_prog=x11',
            'origin' => 'https://myfin.by',
            // 'referer' => 'https://myfin.by/currency/minsk?__cf_chl_tk=hWw6LZUnDi2QB3X1MNzBYdyvoj08jPj8D.wNr6pf23Y-1646767953-0-gaNycGzNCX0',
            'sec-ch-ua' => '" Not A;Brand";v="99", "Chromium";v="98", "Opera";v="84"',
            'sec-ch-ua-mobile' => '?0',
            'sec-ch-ua-platform' => "Windows",
            'sec-fetch-dest' => 'document',
            'sec-fetch-mode' => 'navigate',
            'sec-fetch-site' => 'same-origin',
            'upgrade-insecure-requests' => '1',
            'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.109 Safari/537.36 OPR/84.0.4316.31'
        ])->get($url);
        $contents = $contents->body();
        dd($contents);
        // $contents = file_get_contents($url);
        $document = new Document($contents);

        // $contents = file_get_contents('C:\Users\Admin\Desktop\MYPROJECTS\best-currency-checker\public\submain.html');
        // $document = new Document($contents);

        $info = $document->find('.table-responsive')[0]->find('table')[0];
        $fields = $info->find('tr');

        $name = $fields[0]->find('td')[1]->text();
        $address = $fields[1]->find('td')[1]->text();
        $phones = $fields[2]->find('td')[1]->text();
        $workingTime = implode(', ', array_map(fn (string $elem): string => trim($elem), explode(',', $fields[3]->find('td')[1]->text())));
        $website = trim($fields[4]->find('td')[1]->text());

        $indexOfCoordinates = strpos($contents, 'coords');
        $start = strpos($contents, '[', $indexOfCoordinates);
        $end = strpos($contents, ']', $indexOfCoordinates);
        $coordinates = substr($contents, $start + 1, $end - $start - 1);

        echo "nazvanie: {$name}".PHP_EOL;
        echo "adres: {$address}".PHP_EOL;
        echo "telefon: {$phones} ".PHP_EOL;
        echo "vremya raboti: {$workingTime}".PHP_EOL;
        echo "website: {$website}".PHP_EOL;
        echo "coordinates: {$coordinates}".PHP_EOL;

        return [
            'name' => $name,
            'address' => $address,
            'phones' => $phones,
            'working_time' => $workingTime,
            'website' => $website,
            'coordinates' => explode(',', $coordinates)
        ];
    }
}
