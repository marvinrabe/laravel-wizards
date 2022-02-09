<?php

namespace MarvinRabe\LaravelWizards\Tests\Repositories;

use Illuminate\Support\Facades\Session;
use MarvinRabe\LaravelWizards\Repositories\SessionWizardRepository;
use MarvinRabe\LaravelWizards\Tests\TestCase;
use MarvinRabe\LaravelWizards\Wizard;
use MarvinRabe\LaravelWizards\WizardNotFoundException;

class SessionWizardRepositoryTest extends TestCase
{
    public function testSave(): void
    {
        $wizard = new Wizard([]);

        $repository = new SessionWizardRepository();
        $repository->save($wizard);

        $this->assertTrue(Session::has('wizard.'.$wizard->id));
    }

    public function testGet(): void
    {
        $wizard = new Wizard([]);
        $repository = new SessionWizardRepository();
        $repository->save($wizard);

        $loaded = $repository->get($wizard->id);

        $this->assertEquals($wizard, $loaded);
    }
    public function testThrowsNotFoundException(): void
    {
        $this->expectException(WizardNotFoundException::class);

        $repository = new SessionWizardRepository();
        $repository->get('::MISSING::');
    }

    public function testDelete(): void
    {
        $wizard = new Wizard([]);
        $repository = new SessionWizardRepository();
        $repository->save($wizard);
        $sessionKey = 'wizard.'.$wizard->id;

        $repository->delete($wizard);

        $this->assertFalse(Session::has($sessionKey));
    }
}
