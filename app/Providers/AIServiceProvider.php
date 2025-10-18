<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AIRecommendationService;

class AIServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(AIRecommendationService::class, function ($app) {
            return new AIRecommendationService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}