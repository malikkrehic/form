<?php

declare(strict_types=1);

namespace Mk\Form\Support;

use Mk\Form\Contracts\FormContract;
use Mk\Form\Services\FormService;

/**
 * Form Registrar
 *
 * Provides a fluent interface for registering forms with the FormService.
 * This class simplifies form registration in service providers.
 */
class FormRegistrar
{
    protected FormService $formService;
    protected array $forms = [];

    public function __construct(FormService $formService)
    {
        $this->formService = $formService;
    }

    /**
     * Add a form instance to the registration queue.
     */
    public function add(FormContract $form): self
    {
        $this->forms[] = $form;
        return $this;
    }

    /**
     * Add a form by class name to the registration queue.
     */
    public function addClass(string $formClass): self
    {
        $this->forms[] = $formClass;
        return $this;
    }

    /**
     * Add multiple forms to the registration queue.
     *
     * @param array<FormContract|string> $forms
     */
    public function addMany(array $forms): self
    {
        foreach ($forms as $form) {
            $this->forms[] = $form;
        }
        return $this;
    }

    /**
     * Register forms from a namespace and directory.
     */
    public function fromNamespace(string $namespace, string $directory): self
    {
        $this->formService->registerFormsFromNamespace($namespace, $directory);
        return $this;
    }

    /**
     * Register all queued forms.
     */
    public function register(): void
    {
        $this->formService->registerForms($this->forms);
        $this->forms = [];
    }

    /**
     * Create a new registrar instance.
     */
    public static function make(FormService $formService): self
    {
        return new self($formService);
    }
}
