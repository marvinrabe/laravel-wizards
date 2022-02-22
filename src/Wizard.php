<?php

namespace MarvinRabe\LaravelWizards;

use Illuminate\Support\Str;

class Wizard
{
    public string|null $id;

    public int $step = 1;

    public int $maxSteps = 0;

    public mixed $payload = null;

    public function __construct()
    {
        $this->id = (string) Str::uuid();
    }

    public function setPayload(mixed $payload): self {
        $this->payload = $payload;
        return $this;
    }
}
