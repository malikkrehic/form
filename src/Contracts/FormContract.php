<?php

declare(strict_types=1);

namespace Mk\Form\Contracts;

/**
 * Form Contract
 *
 * Defines the interface that all forms must implement.
 */
interface FormContract
{
    /**
     * Configure the form properties.
     */
    public function configure(): void;

    /**
     * Get the form fields.
     */
    public function fields(): array;

    /**
     * Get the form name.
     */
    public function getName(): string;

    /**
     * Get the form title.
     */
    public function getTitle(): string;

    /**
     * Get the form endpoint.
     */
    public function getEndpoint(): string;

    /**
     * Get the form method.
     */
    public function getMethod(): string;

    /**
     * Get the form configuration.
     */
    public function getConfiguration(): array;

    /**
     * Get validation messages.
     */
    public function getMessages(): array;

    /**
     * Get the form validation rules.
     */
    public function rules(): array;

    /**
     * Convert the form to an array representation.
     */
    public function toArray(): array;

    /**
     * Handle the form submission.
     * @param \Illuminate\Http\Request|array $request The HTTP request or form data
     */
    public function handle(mixed $request): mixed;

    /**
     * Get success messages.
     */
    public function getSuccessMessages(): array;
}
