<?php

declare(strict_types=1);

namespace Mk\Form\Contracts;

/**
 * Form Field Contract
 *
 * Defines the interface that all form fields must implement.
 */
interface FormFieldContract
{
    /**
     * Create a new field instance.
     */
    public static function make(string $name): self;

    /**
     * Set the field name.
     */
    public function setName(string $name): self;

    /**
     * Set the field label.
     */
    public function setLabel(string $label): self;

    /**
     * Set the field type.
     */
    public function type(string $type): self;

    /**
     * Set whether the field is required.
     */
    public function setRequired(bool $required = true): self;

    /**
     * Set the validation rules.
     */
    public function rules(array $rules): self;

    /**
     * Set the field options.
     */
    public function setOptions(array|string $options): self;

    /**
     * Set the default value.
     */
    public function setDefaultValue(mixed $value): self;

    /**
     * Set the placeholder text.
     */
    public function setPlaceholder(string $placeholder): self;

    /**
     * Set help text.
     */
    public function setHelpText(string $helpText): self;

    /**
     * Set a custom component.
     */
    public function component(string $component): self;

    /**
     * Set additional props.
     */
    public function props(array $props): self;

    /**
     * Convert the field to an array representation.
     */
    public function toArray(): array;
}
