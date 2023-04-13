<?php

declare(strict_types = 1);

namespace Wame\ApiResponse;

use Illuminate\Support\ServiceProvider;

class LaravelApiResponseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     * @return void
     */
    public function boot():void
    {
        // Export translations
        $this->publishTranslations();
    }
    
    /**
     * @return void
     */
    protected function publishTranslations(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'wame-api-response');
    }
}
