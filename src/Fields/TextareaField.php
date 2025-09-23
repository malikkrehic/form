<?php

declare(strict_types=1);

namespace Mk\Form\Fields;

/**
 * Textarea Field
 *
 * A textarea field for multi-line text input.
 */
class TextareaField extends FormField
{
    /**
     * Create a new textarea field.
     */
    public static function make(string $name): self
    {
        $instance = parent::make($name);
        $instance->type('textarea');
        return $instance;
    }

    /**
     * Set the number of rows.
     */
    public function rows(int $rows): self
    {
        $this->props['rows'] = $rows;
        return $this;
    }

    /**
     * Set the number of columns.
     */
    public function cols(int $cols): self
    {
        $this->props['cols'] = $cols;
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
}
