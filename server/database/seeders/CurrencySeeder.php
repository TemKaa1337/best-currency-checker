<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\BankCurrencyInfo;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $splitPhones = function (string $phones): string {
            return array_map(fn (string $elem): string => trim($elem), preg_split('/(;|,)/', $phones));
        };

        $departments = [
            [
                "name" => 'Головное отделение ЗАО "Абсолютбанк"',
                "address" => "г. Минск, пр. Независимости, 95",
                "phones" => $splitPhones("+375-29-675-45-78, +375-33-675-45-78, +375-25-675-45-78, +375-17-239-70-75, 7545"),
                "working_time" => "Пн-Пт: 09:00-19:00, Сб-Вс: Выходной",
                "website" => "absolutbank.by",
                "coordinates" => ["53.925785", "27.616836"],
                "last_update" => date('Y-m-d')." 09:16".':00',
                "currency_info" => [
                    "usd" => [
                        "bank_buys" => "3.58",
                        "bank_sells" => "3.85"
                    ],
                    "eur" => [
                        "bank_buys" => "3.82",
                        "bank_sells" => "4.12"
                    ]
                ],
                'bank_name' => 'Абсолютбанк'
            ],
            [
                "name" => 'Касса № 24 ЗАО "Абсолютбанк"',
                "address" => 'г. Минск, пр-т Независимости, 154 (ТЦ "Корона")',
                "phones" => $splitPhones("+375-29-675-45-78; +375-33-675-45-78; +375-25-675-45-78; 7545 (контакт-центр), +375-17-239-70-75"),
                "working_time" => "Пн-Пт: 09:10-21:35 Перерыв: 20:15-20:30, Сб-Вс: 09:00-21:35 Перерыв: 20:15-20:30",
                "website" => "absolutbank.by",
                "coordinates" => ["53.936519", "27.673331"],
                "last_update" => date('Y-m-d')." 09:16".':00',
                "currency_info" => [
                    "usd" => [
                        "bank_buys" => "3.53",
                        "bank_sells" => "3.89"
                    ],
                    "eur" => [
                        "bank_buys" => "3.75",
                        "bank_sells" => "4.2"
                    ]
                ],
                'bank_name' => 'Абсолютбанк'
            ],
            [
                "name" => 'Касса № 38 ЗАО "Абсолютбанк"',
                "address" => 'г. Минск, пр-т Независимости, 154 (ТЦ "Корона")',
                "phones" => $splitPhones("+375-29-675-45-78; +375-33-675-45-78; +375-25-675-45-78; 7545 (контакт-центр), +375-17-239-70-75"),
                "working_time" => "Пн-Пт: 09:10-21:35 Перерыв: 13:45-14:00, 15:45-16:30, 20:30-20:45, Сб-Вс: 09:00-21:35 Перерыв: 13:45-14:00, 15:45-16:30, 20:30-20:45",
                "website" => "absolutbank.by",
                "coordinates" => ["53.936376973336294", "27.673358161704368"],
                "last_update" => date('Y-m-d')." 09:16".':00',
                "currency_info" => [
                    "usd" => [
                        "bank_buys" => "3.53",
                        "bank_sells" => "3.89"
                    ],
                    "eur"=> [
                        "bank_buys" => "3.75",
                        "bank_sells" => "4.2"
                    ]
                ],
                'bank_name' => 'Абсолютбанк'
            ],
            [
                "name" => 'Офис "Запад" ЗАО "Абсолютбанк"',
                "address" => "г. Минск, ул. Притыцкого, 77",
                "phones" => $splitPhones("+375-29-675-45-78; +375-33-675-45-78; +375-25-675-45-78; 7545 (контакт-центр)"),
                "working_time" => "Пн-Пт: 09:30-19:00, Сб-Вс: Выходной",
                "website" => "absolutbank.by",
                "coordinates" => ["53.905375574665165", "27.457999831343304"],
                "last_update" => date('Y-m-d')." 09:16".':00',
                "currency_info" => [
                    "usd" => [
                        "bank_buys" => "3.58",
                        "bank_sells" => "3.85"
                    ],
                    "eur" => [
                        "bank_buys" => "3.82",
                        "bank_sells" => "4.12"
                    ]
                ],
                'bank_name' => 'Абсолютбанк'
            ],
            [
                "name" => 'Офис «Грушевка» ЗАО "Абсолютбанк"',
                "address" => "г. Минск, ул. Щорса,11",
                "phones" => $splitPhones("+375-29-675-45-78; +375-33-675-45-78; +375-25-675-45-78; 7545 (контакт-центр)"),
                "working_time" => "Пн-Пт: 10:00-19:00, Сб-Вс: Выходной",
                "website" => "absolutbank.by",
                "coordinates" => ["53.88568963719372", "27.515069653106"],
                "last_update" => date('Y-m-d')." 09:16".':00',
                "currency_info" => [
                    "usd" => [
                        "bank_buys" => "3.58",
                        "bank_sells" => "3.85"
                    ],
                    "eur" => [
                        "bank_buys"=> "3.82",
                        "bank_sells"=> "4.12"
                    ]
                ],
                'bank_name' => 'Абсолютбанк'
            ],
            [
                "name" => 'Офис «На Денисовской» ЗАО "Абсолютбанк"',
                "address" => "г. Минск, ул. Денисовская, 8",
                "phones" => $splitPhones("7545, +375-29-675-45-78, +375-33-675-45-78, +375-25-675-45-78, +375-17-239-70-75"),
                "working_time" => "Пн-Пт: 10:20-19:00, Сб-Вс: 10:40-20:00",
                "website" => "absolutbank.by",
                "coordinates" => ["53.871594", "27.572799"],
                "last_update" => date('Y-m-d')." 09:16".':00',
                "currency_info" => [
                    "usd" => [
                        "bank_buys" => "3.58",
                        "bank_sells" => "3.85"
                    ],
                    "eur" => [
                        "bank_buys"=> "3.82",
                        "bank_sells"=> "4.12"
                    ]
                ],
                'bank_name' => 'Абсолютбанк'
            ],
            [
                "name" => 'Офис «Порт» ЗАО "Абсолютбанк"',
                "address" => "г. Минск, пр-т Независимости, 177-6",
                "phones" => $splitPhones("+375-29-675-45-78; +375-33-675-45-78; +375-25-675-45-78; 7545 (контакт-центр), +375-17-239-70-75, юрлицам: +375-29-636-62-25, +375-17-239-70-85"),
                "working_time" => "Пн-Пт: 09:00-19:00, Сб-Вс: Выходной",
                "website" => "absolutbank.by",
                "coordinates" => ["53.945595426313645", "27.68332114517147"],
                "last_update" => date('Y-m-d')." 09:16".':00',
                "currency_info" => [
                    "usd" => [
                        "bank_buys"=> "3.58",
                        "bank_sells"=> "3.85"
                    ],
                    "eur"=> [
                        "bank_buys" => "3.82",
                        "bank_sells" => "4.12"
                    ]
                ],
                'bank_name' => 'Абсолютбанк'
            ],
            [
                "name" => 'ПОВ № 43 ЗАО "Абсолютбанк"',
                "address" => "г. Минск, ул. Кальварийская, 24 (Гипермаркет «Корона»)",
                "phones" => $splitPhones("+375-29-675-45-78; +375-33-675-45-78; +375-25-675-45-78; 7545 (контакт-центр)"),
                "working_time" => "Пн-Пт: 10:30-21:10 Перерыв: 19:45-20:00, Сб-Вс: 10:20-21:10 Перерыв: 19:45-20:00",
                "website" => "absolutbank.by",
                "coordinates" => ["53.908039", "27.527327"],
                "last_update" => date('Y-m-d')." 09:16".':00',
                "currency_info" => [
                    "usd" => [
                        "bank_buys" => "3.53",
                        "bank_sells" => "3.89"
                    ],
                    "eur"=> [
                        "bank_buys" => "3.75",
                        "bank_sells" => "4.2"
                    ]
                ],
                'bank_name' => 'Абсолютбанк'
            ],
            [
                "name" => 'ПОВ № 44 ЗАО "Абсолютбанк"',
                "address" => "г. Минск, ул. Кальварийская, 24 (Гипермаркет «Корона»)",
                "phones" => $splitPhones("+375-29-675-45-78; +375-33-675-45-78; +375-25-675-45-78; 7545 (контакт-центр)"),
                "working_time" => "Пн-Пт: 10:30-21:10 Перерыв: 19:15-19:30, Сб-Вс: 10:20-21:10 Перерыв: 19:15-19:30",
                "website" => "absolutbank.by",
                "coordinates" => ["53.908039", "27.527327"],
                "last_update" => date('Y-m-d')." 09:16".':00',
                "currency_info" => [
                    "usd" => [
                        "bank_buys" => "3.53",
                        "bank_sells" => "3.89"
                    ],
                    "eur" => [
                        "bank_buys" => "3.75",
                        "bank_sells" => "4.2"
                    ]
                ],
                'bank_name' => 'Абсолютбанк'
            ]
        ];

        foreach ($departments as $department) {
            BankCurrencyInfo::create($department);
        }
    }
}
