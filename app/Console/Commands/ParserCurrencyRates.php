<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\BankCurrencyInfo;
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
        $this->parseMainPage();

        return 0;
    }

    private function parseMainPage(): void
    {
        // $document = new Document('https://myfin.by/currency/minsk');
        $document = new Document('C:\Users\Admin\Desktop\MYPROJECTS\best-currency-checker\public\main.html', true);

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
            'Referer' => 'https://myfin.by/currency/minsk',
            'sec-ch-ua' => '" Not A;Brand";v="99", "Chromium";v="99", "Google Chrome";v="99"',
            'sec-ch-ua-mobile' => '?0',
            'sec-ch-ua-platform' => "Windows",
            'Upgrade-Insecure-Requests' => '1',
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.51 Safari/537.36'
        ])->get($url);
        $contents = $contents->body();
        dd($contents);
        $contents = file_get_contents($url);
        $document = new Document($url);

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
