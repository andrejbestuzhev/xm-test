<?php

namespace App\Providers;

use App\Console\Commands\RefreshSymbolValues;
use App\Services\DataProviderServices\AlphaVantageService;
use App\Services\DataProviderServices\DataProviderServiceInterface;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AlphaVantageService::class, function ($app) {
            return new AlphaVantageService(
                config('services.alpha_vantage.url'),
                config('services.alpha_vantage.key'),
                new Client()
            );
        });

        // may be configured
        $this->app->bind(DataProviderServiceInterface::class, AlphaVantageService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
