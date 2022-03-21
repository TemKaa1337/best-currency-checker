<?php

namespace App\Services\Traits\Parser;

use App\Models\Logging;
use App\Services\Traits\Parser\Helper;
use DiDom\Document;

trait Page
{
    use Helper;

    protected function getDataFromMainPage(string $html, string $city): void
    {
        $document = new Document($html);

        $departments = $document->find('table');
        array_shift($departments);
        array_shift($departments);
        $updates = [];

        foreach ($departments as $department) {
            $bank = trim(str_replace('Отделения ', '', $department->find('thead')[0]->find('th')[0]->text()));

            $tbody = $department->find('tbody')[0];
            $update = [];
            echo "bank {$bank}".PHP_EOL;
            echo "city: {$city}".PHP_EOL;

            $trs = $tbody->find('tr');

            foreach ($trs as $currentDepartment) {
                $tds = $currentDepartment->find('td');
                $departmentInfo = $tds[0]->find('div');
                $innerPageLink = 'https://myfin.by'.$departmentInfo[0]->find('a')[0]->href;

                $departmentInfoCount = count($departmentInfo);
                $lastUpdateIndex = $departmentInfoCount === 4 ? 3 : 1;
                $lastUpdate = $this->trimLastUpdate(trim($departmentInfo[$lastUpdateIndex]->text()));

                if ($this->type === 'request') {
                    $innerInfo = $this->parseInnerPageWithRequest($innerPageLink);
                } else {
                    $innerInfo = $this->parseInnerPageWithBrowser($innerPageLink);
                }

                $bankBuysUsd = $tds[1]->text();
                $bankSellsUsd = $tds[2]->text();
                $bankBuysEur = $tds[3]->text();
                $bankSellsEur = $tds[4]->text();

                $update[] = array_merge($innerInfo, [
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
                    ],
                    'bank_name' => $bank,
                    'city' => $city
                ]);

                $updates[] = $update;

                echo "last update {$lastUpdate}".PHP_EOL;
                echo "inner page link {$innerPageLink}".PHP_EOL;
                echo "pokupka usd {$bankBuysUsd}".PHP_EOL;
                echo "drodaja usd {$bankSellsUsd}".PHP_EOL;
                echo "pokupka eur {$bankBuysEur}".PHP_EOL;
                echo "pokupka eur {$bankSellsEur}".PHP_EOL;
            }

            $this->update($update);
        }

        if (empty($updates)) {
            Logging::info(
                classname: $this->type === 'request' ? RequestParser::class : BrowserParser::class,
                success: false
            );
        }
    }

    protected function getDataFromInnerPage(string $html): array
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
}
?>
