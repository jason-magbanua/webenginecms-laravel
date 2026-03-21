<?php

namespace App\Providers;

use App\Auth\MuOnlineUserProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Auth::provider('muonline', function ($app, array $config) {
            return new MuOnlineUserProvider();
        });
    }
}
