<?php

namespace MarvinRabe\LaravelWizards\Tests;

use Illuminate\Support\Str;
use MarvinRabe\LaravelWizards\Wizard;

class WizardTest extends TestCase
{
    public function testIdIsUuid(): void
    {
        $wizard = new Wizard();

        $this->assertTrue(Str::isUuid($wizard->id));
    }

    public function testDefaultPayload(): void
    {
        $wizard = new Wizard();

        $this->assertNull($wizard->payload);
    }

    public function testSetPayload(): void
    {
        $wizard = new Wizard();

        $wizard->setPayload('::PAYLOAD::');

        $this->assertEquals('::PAYLOAD::', $wizard->payload);
    }
}
