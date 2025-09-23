<?php

declare(strict_types=1);

namespace Mk\Form\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Form Facade
 *
 * Provides easy access to the FormService.
 */
class Form extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return \Mk\Form\Services\FormService::class;
    }
}
