<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logging extends Model
{
    use HasFactory;

    protected $fillable = ['*'];
    protected $guarded = [];

    public static function info(
        string $classname,
        bool $success
    ): void
    {
        self::insert([
            'classname' => $classname,
            'type' => 'info',
            'success' => $success
        ]);
    }

    public static function error(
        string $classname,
        array $info
    ): void
    {
        self::insert([
            'classname' => $classname,
            'type' => 'error',
            'success' => false,
            'info' => json_encode($info)
        ]);
    }
}
