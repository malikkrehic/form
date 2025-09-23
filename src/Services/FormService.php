<?php

declare(strict_types=1);

namespace Mk\Form\Services;

use Illuminate\Http\Request;
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
        $this->forms[$this->getFormKey($form)] = $form;
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
        $validator = validator($data, $form->rules());

        if ($validator->fails()) {
            return $validator->errors()->toArray();
        }

        return [];
    }

    /**
     * Process form submission.
     */
    public function processFormSubmission(string $formName, Request $request): mixed
    {
        $form = $this->getForm($formName);

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
     */
    protected function getFormKey(FormContract $form): string
    {
        $endpoint = $form->getEndpoint();

        // Extract form name from endpoint (e.g., "/forms/create-user" -> "create-user")
        $parts = explode('/', trim($endpoint, '/'));
        if (count($parts) >= 2 && $parts[0] === 'forms') {
            return $parts[1];
        }

        // Fallback to class name
        $className = get_class($form);
        $className = basename(str_replace('\\', '/', $className));
        return strtolower(str_replace('Form', '', $className));
    }

    /**
     * Clear all registered forms.
     */
    public function clearForms(): void
    {
        $this->forms = [];
    }
}
