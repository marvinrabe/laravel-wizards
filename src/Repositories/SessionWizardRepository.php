<?php

namespace MarvinRabe\LaravelWizards\Repositories;

use Illuminate\Support\Facades\Session;
use MarvinRabe\LaravelWizards\Wizard;
use MarvinRabe\LaravelWizards\WizardNotFoundException;
use MarvinRabe\LaravelWizards\WizardRepository;

class SessionWizardRepository implements WizardRepository
{
    private string $keyPrefix = 'wizard.';

    private function sessionKey(Wizard|string $wizard): string
    {
        return $this->keyPrefix.($wizard instanceof Wizard ? $wizard->id : $wizard);
    }

    public function save(Wizard $wizard): void
    {
        Session::put($this->sessionKey($wizard), $wizard);
    }

    public function get(string $id): Wizard
    {
        $sessionKey = $this->sessionKey($id);

        if (!Session::has($sessionKey)) {
            throw new WizardNotFoundException();
        }

        return Session::get($sessionKey);
    }

    public function delete(Wizard $wizard): void
    {
        $sessionKey = $this->sessionKey($wizard);

        if (!Session::has($sessionKey)) {
            return;
        }

        Session::forget($sessionKey);
        $wizard->id = null;
    }
}
