<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{
    Factories\HasFactory,
    Casts\Attribute,
    Model
};

class BankCurrencyInfo extends Model
{
    use HasFactory;

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $casts = [
        'phones' => 'array',
        'coordinates' => 'array',
        'currency_info' => 'array'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'last_update'
    ];
}
