<?php

namespace MarvinRabe\LaravelWizards;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

abstract class WizardController
{
    public function __construct(protected WizardRepository $repository)
    {
    }

    public function __invoke(Request $request): mixed
    {
        $id = $request->route('wizard');
        $step = $request->route('step');

        // Prepare new Wizard
        if ($id === null) {
            $wizard = new Wizard();
            $payload = $this->preparePayload($request, $wizard);
            $wizard->setPayload($payload);
            $this->repository->save($wizard);
            return $this->redirect($wizard);
        }

        $wizard = $this->repository->get($id);

        // Redirect to latest Step
        if ($step === null) {
            return $this->redirect($wizard);
        }

        return $request->method() === Request::METHOD_GET
            ? $this->handleGet($request, $wizard, $step)
            : $this->handlePost($request, $wizard, $step);
    }

    protected function redirect(Wizard $wizard): RedirectResponse
    {
        return Redirect::action($this::class, [
            ...\Illuminate\Support\Facades\Request::route()->parameters(),
            'wizard' => $wizard->id,
            'step' => $wizard->step
        ]);
    }

    protected function handleGet(Request $request, Wizard $wizard, int $step): mixed
    {
        $method = 'step'.$step;
        if (!method_exists($this, $method)) {
            abort(404);
        }

        if ($wizard->step < $step) {
            return $this->redirect($wizard);
        }

        return $this->{$method}($request, $wizard);
    }

    protected function handlePost(Request $request, Wizard $wizard, int $step): mixed
    {
        if ($wizard->step < $step) {
            return $this->redirect($wizard);
        }

        $method = 'step'.$step.'Submit';
        if (method_exists($this, $method)) {
            $this->{$method}($request, $wizard);
        }

        $wizard->step = $step + 1;

        if (method_exists($this, 'step'.$wizard->step)) {
            $this->repository->save($wizard);

            return $this->redirect($wizard);
        } else {
            $this->repository->delete($wizard);

            return $this->onFinish($request, $wizard);
        }
    }

    abstract function preparePayload(Request $request, Wizard $wizard): mixed;

    abstract function onFinish(Request $request, Wizard $wizard): mixed;
}
