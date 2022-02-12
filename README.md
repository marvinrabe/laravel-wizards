# Laravel Wizards

[![Latest Version on Packagist](https://img.shields.io/packagist/v/marvinrabe/laravel-wizards.svg?style=flat-square)](https://packagist.org/packages/marvinrabe/laravel-wizards)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/marvinrabe/laravel-wizards/Tests?label=tests)](https://github.com/marvinrabe/laravel-wizards/actions?query=workflow%3ATests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/marvinrabe/laravel-wizards.svg?style=flat-square)](https://packagist.org/packages/marvinrabe/laravel-wizards)

Simple Wizard Controller for Laravel. If you need a more sophisticated solution, then have a look
at [Laravel Aracanist](https://laravel-arcanist.com).

## Installation

Install this package via composer:

```bash
composer require marvinrabe/laravel-wizards
```

## Usage

Register a route using the `wizard` macro in your `web.php`:

```php
Route::wizard('order', OrderWizardController::class);
```

Create a controller for your wizard. For example `OrderWizardController.php`:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MarvinRabe\LaravelWizards\Wizard;
use MarvinRabe\LaravelWizards\WizardController;

class FileImportController extends WizardController
{
    // Prepare a payload. This payload will be available in $wizard->payload on each step. It will be saved
    // automatically after each step{number}Submit method. It is used to store each partial result until onFinish.
    // The simplest payload is an array. But you could use anything you want. It only needs to be serializable!
    public function preparePayload(Request $request): array
    {
        return [];
    }

    // A step is simply a method with the name step{number}.
    // You can do anything you want! Return a view, redirect to somewhere else, etc.
    public function step1(Request $request, Wizard $wizard): mixed
    {
        return view('order.payment');
    }

    // For each step there might be a submit action with the name step{number}Submit. This will be called on POST.
    // In here you could validate the request and afterwards store it in the prepared payload like so:
    public function step1Submit(Request $request, Wizard $wizard): void
    {
        $request->validate([
            'credit_card' => ['required', 'string']
        ]);

        $wizard->payload['credit_card'] = $request->boolean('credit_card');
    }

    // You don't have to specify a step2Submit. If you omit this method the wizard will simply jump to the next step.
    // In each step you have access to the previously populated payload. By default it is stored in the session.
    public function step2(Request $request, Wizard $wizard): mixed
    {
        return view('order.summary', [
            'credit_card' => $wizard->payload['credit_card']
        ]);
    }
    
    // Continue with more step{number} and step{number}Submit methods. As many as you like!
    
    // After the last step the onFinish method will be called instead of the step{number}Submit.
    // The wizard will be deleted from the session and is no longer available.
    public function onFinish(Request $request, Wizard $wizard): mixed
    {
        // Do something! This is your final chance.
        return view('order.success');
    }
}
```

Navigate to `/orders` to start a wizard. Each wizard has a unique ID and is by default stored in the current Session.
The URL follows the following convention:

```
/{name}/{id?}/{step?}
```

For our previous example a real URL might look like this `/order/6e00f3db-c1c9-48b7-a90b-0081f75ed56c/2`.

If the id is missing it will create a new wizard instance using `preparePayload`. If the step is missing it will resume
the wizard at the last step.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
