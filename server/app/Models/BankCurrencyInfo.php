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

    protected $casts = [
        'phones' => 'array',
        'coordinates' => 'array',
        'currency_info' => 'array'
    ];
}
