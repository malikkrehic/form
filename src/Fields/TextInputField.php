<?php

declare(strict_types=1);

namespace Mk\Form\Fields;

/**
 * Text Input Field
 *
 * A basic text input field with optional validation and formatting.
 */
class TextInputField extends FormField
{
    /**
     * Create a new text input field.
     */
    public static function make(string $name): self
    {
        $instance = parent::make($name);
        $instance->type('text');
        return $instance;
    }

    /**
     * Set the input type (text, email, password, etc.).
     */
    public function inputType(string $inputType): self
    {
        $this->type($inputType);
        return $this;
    }

    /**
     * Set the maximum length.
     */
    public function maxLength(int $maxLength): self
    {
        $this->props['maxlength'] = $maxLength;
        $this->rules(["max:{$maxLength}"]);
        return $this;
    }

    /**
     * Set the minimum length.
     */
    public function minLength(int $minLength): self
    {
        $this->props['minlength'] = $minLength;
        $this->rules(["min:{$minLength}"]);
        return $this;
    }

    /**
     * Set pattern for input validation.
     */
    public function pattern(string $pattern): self
    {
        $this->props['pattern'] = $pattern;
        return $this;
    }
}
