<?php

namespace App\Console\Commands\Parser;

use Illuminate\Support\Facades\Http;
use App\Services\Traits\Parser\Page;
use Illuminate\Console\Command;

class RequestParser extends Command
{
    use Page;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected string $type = 'request';

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
        $this->parseMainPageWithRequest();

        return 0;
    }

    private function parseMainPageWithRequest(): void
    {
        $response = Http::get('https://myfin.by/currency/minsk');
        $this->getDataFromMainPage($response->body());
    }

    private function parseInnerPageWithRequest(string $url): array
    {
        sleep(1);

        while (True) {
            $response = Http::get($url);
            if ($response->status() === 200) break;

            echo "Got status code: {$response->status()}, waiting 5 seconds...";
            sleep(5);
        }

        return $this->getDataFromInnerPage($response->body());
    }
    // private function getDataFromMainPage(string $html): void
    // {
    //     $document = new Document($html);

    //     $departments = $document->find('table');
    //     array_shift($departments);
    //     array_shift($departments);

    //     foreach ($departments as $department) {
    //         $bank = trim(str_replace('Отделения ', '', $department->find('thead')[0]->find('th')[0]->text()));

    //         $tbody = $department->find('tbody')[0];
    //         $this->overallInfo = [];
    //         echo "bank {$bank}".PHP_EOL;

    //         $trs = $tbody->find('tr');

    //         foreach ($trs as $currentDepartment) {
    //             $tds = $currentDepartment->find('td');
    //             $departmentInfo = $tds[0]->find('div');
    //             $innerPageLink = 'https://myfin.by'.$departmentInfo[0]->find('a')[0]->href;
    //             $address = $departmentInfo[0]->text();

    //             $lastUpdate = $this->trimLastUpdate(trim($departmentInfo[1]->text()));

    //             if ($this->type === 'request') {
    //                 $innerInfo = $this->parseInnerPageWithRequest($innerPageLink);
    //             } else {
    //                 $innerInfo = $this->parseInnerPageWithBrowser($innerPageLink);
    //             }

    //             $bankBuysUsd = $tds[1]->text();
    //             $bankSellsUsd = $tds[2]->text();
    //             $bankBuysEur = $tds[3]->text();
    //             $bankSellsEur = $tds[4]->text();

    //             $this->overallInfo[] = array_merge($innerInfo, [
    //                 'last_update' => $lastUpdate,
    //                 'currency_info' => json_encode([
    //                     'usd' => [
    //                         'bank_buys' => $bankBuysUsd,
    //                         'bank_sells' => $bankSellsUsd
    //                     ],
    //                     'eur' => [
    //                         'bank_buys' => $bankBuysEur,
    //                         'bank_sells' => $bankSellsEur
    //                     ]
    //                 ]),
    //                 'bank_name' => $bank
    //             ]);

    //             echo "department with address {$address}".PHP_EOL;
    //             echo "last update {$lastUpdate}".PHP_EOL;
    //             echo "inner page link {$innerPageLink}".PHP_EOL;
    //             echo "pokupka usd {$bankBuysUsd}".PHP_EOL;
    //             echo "drodaja usd {$bankSellsUsd}".PHP_EOL;
    //             echo "pokupka eur {$bankBuysEur}".PHP_EOL;
    //             echo "pokupka eur {$bankSellsEur}".PHP_EOL;
    //         }

    //         $this->update();
    //     }
    // }


    // private function getDataFromInnerPage(string $html): array
    // {
    //     $document = new Document($html);
    //     $info = $document->find('.table-responsive')[0]->find('table')[0];

    //     $fields = $info->find('tr');

    //     $name = $fields[0]->find('td')[1]->text();
    //     $address = $fields[1]->find('td')[1]->text();
    //     $phones = $this->splitPhones($fields[2]->find('td')[1]->text());
    //     $workingTime = implode(', ', array_map(fn (string $elem): string => trim($elem), explode(',', $fields[3]->find('td')[1]->text())));
    //     $website = trim($fields[4]->find('td')[1]->text());

    //     $indexOfCoordinates = strpos($html, 'coords');
    //     $start = strpos($html, '[', $indexOfCoordinates);
    //     $end = strpos($html, ']', $indexOfCoordinates);
    //     $coordinates = substr($html, $start + 1, $end - $start - 1);

    //     echo "nazvanie: {$name}".PHP_EOL;
    //     echo "adres: {$address}".PHP_EOL;
    //     $viewPhones = implode(',', $phones);
    //     echo "telefon: {$viewPhones} ".PHP_EOL;
    //     echo "vremya raboti: {$workingTime}".PHP_EOL;
    //     echo "website: {$website}".PHP_EOL;
    //     echo "coordinates: {$coordinates}".PHP_EOL;

    //     return [
    //         'name' => $name,
    //         'address' => $address,
    //         'phones' => json_encode($phones),
    //         'working_time' => $workingTime,
    //         'website' => $website,
    //         'coordinates' => json_encode(array_map('trim', explode(',', $coordinates)))
    //     ];
    // }

    // private function splitPhones(string $phones): array
    // {
    //     return array_map(fn (string $elem): string => trim($elem), preg_split('/(;|,)/', $phones));
    // }

    // private function trimLastUpdate(string $lastUpdate): string
    // {
    //     $lastUpdate = trim($lastUpdate);

    //     if (mb_strlen($lastUpdate) > 5) {
    //         $lastUpdate = preg_replace('!\s+!', ' ', $lastUpdate);
    //         $dateTimeInfo = explode(' ', $lastUpdate);
    //         array_pop($dateTimeInfo);

    //         $lastUpdate = implode(' ', [$dateTimeInfo[0], $dateTimeInfo[1]]);
    //     }

    //     return $lastUpdate;
    // }
}
