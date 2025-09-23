<?php

declare(strict_types=1);

namespace Mk\Form\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mk\Form\Contracts\FormContract;

/**
 * Form Service
 *
 * Handles form registration, retrieval, and validation.
 * Provides a centralized way to manage forms in the application.
 */
class FormService
{
    /**
     * Registered forms.
     */
    protected array $forms = [];

    /**
     * Register a form instance.
     */
    public function registerForm(FormContract $form): void
    {
        $this->forms[$form->getName()] = $form;
    }

    /**
     * Register a form by class name.
     */
    public function registerFormByClass(string $formClass): void
    {
        if (!class_exists($formClass)) {
            throw new \InvalidArgumentException("Form class {$formClass} does not exist.");
        }

        if (!is_subclass_of($formClass, FormContract::class)) {
            throw new \InvalidArgumentException("Form class {$formClass} must implement FormContract.");
        }

        $form = new $formClass();
        $this->registerForm($form);
    }

    /**
     * Get a form by name.
     */
    public function getForm(string $formName): FormContract
    {
        $form = $this->forms[$formName] ?? null;

        if ($form === null) {
            throw new \InvalidArgumentException("Form {$formName} not found.");
        }

        return $form;
    }

    /**
     * Get all registered forms.
     */
    public function getAllForms(): array
    {
        $forms = [];
        foreach ($this->forms as $key => $form) {
            $forms[$key] = $form->toArray();
        }
        return $forms;
    }

    /**
     * Check if a form is registered.
     */
    public function hasForm(string $formName): bool
    {
        return isset($this->forms[$formName]);
    }

    /**
     * Validate form submission data.
     */
    public function validateFormSubmission(FormContract $form, array $data): array
    {
        $validator = Validator::make($data, $form->rules());

        if ($validator->fails()) {
            return $validator->errors()->toArray();
        }

        return [];
    }

    /**
     * Process form submission.
     */
    public function processFormSubmission(string $formName, mixed $request): mixed
    {
        $form = $this->getForm($formName);

        // If request is an array, convert to Request object for validation
        if (is_array($request)) {
            // Create a fresh Request instance populated with the provided data
            $request = Request::create('', 'POST', $request);
        }

        // Validate the data
        $errors = $this->validateFormSubmission($form, $request->all());
        if (!empty($errors)) {
            return [
                'success' => false,
                'errors' => $errors,
            ];
        }

        // Handle the form
        try {
            $result = $form->handle($request);
            return [
                'success' => true,
                'result' => $result,
                'messages' => $form->getSuccessMessages(),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get the form key for registration.
     * @deprecated Use $form->getName() directly instead
     */
    protected function getFormKey(FormContract $form): string
    {
        return $form->getName();
    }

    /**
     * Clear all registered forms.
     */
    public function clearForms(): void
    {
        $this->forms = [];
    }
}
