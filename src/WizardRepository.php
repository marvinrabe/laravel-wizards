<?php

namespace MarvinRabe\LaravelWizards;

interface WizardRepository
{
    public function save(Wizard $wizard): void;

    public function get(string $id): Wizard;

    public function delete(Wizard $wizard): void;
}
