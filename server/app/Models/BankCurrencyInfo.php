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

    protected function phones(): Attribute
    {
        return Attribute::make(
            fn (string $value): array => json_decode($value)
        );
    }

    protected function coordinates(): Attribute
    {
        return Attribute::make(
            fn (string $value): array => json_decode($value)
        );
    }

    protected function currencyInfo(): Attribute
    {
        return Attribute::make(
            fn (string $value): array => json_decode($value)
        );
    }
}
