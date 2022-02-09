<?php

namespace MarvinRabe\LaravelWizards;

use MarvinRabe\LaravelWizards\Repositories\SessionWizardRepository;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->app->bind(WizardRepository::class, SessionWizardRepository::class);
    }
}
