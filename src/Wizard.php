<?php

namespace MarvinRabe\LaravelWizards;

use Illuminate\Support\Str;

class Wizard
{
    public string|null $id;

    public int $step = 1;

    public int $maxSteps = 0;

    public function __construct(public mixed $payload)
    {
        $this->id = Str::uuid();
    }
}
