<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{
    Factories\HasFactory,
    Model
};

class BankCurrencyInfo extends Model
{
    use HasFactory;

    protected $fillable = ['*'];

    protected $casts = [
        'phones' => 'array',
        'coordinates' => 'array',
        'currency_info' => 'array',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'last_update' => 'datetime:Y-m-d H:i:s'
    ];
}
