<?php

namespace MarvinRabe\LaravelWizards\Tests;

use Illuminate\Support\Str;
use MarvinRabe\LaravelWizards\Wizard;

class WizardTest extends TestCase
{
    public function testIdIsUuid(): void
    {
        $wizard = new Wizard([]);

        $this->assertTrue(Str::isUuid($wizard->id));
    }
}
