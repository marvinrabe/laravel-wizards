<?php

namespace MarvinRabe\LaravelWizards;

use Illuminate\Support\Facades\Route;
use MarvinRabe\LaravelWizards\Repositories\SessionWizardRepository;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(WizardRepository::class, SessionWizardRepository::class);
    }

    public function boot(): void
    {
        Route::macro('wizard', function (string $uri, mixed $action) {
            return Route::match(['get', 'post'], $uri.'/{id?}/{step?}', $action);
        });
    }
}
