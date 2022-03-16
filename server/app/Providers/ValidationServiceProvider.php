<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['validator']->extend('numericarray', function ($attributes, $value, $parameters) {
            var_dump($attributes);

            foreach ($attributes as $attribute) {
                var_dump($attribute);
            }

            return true;
        });
    }
}
