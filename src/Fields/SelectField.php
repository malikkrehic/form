<?php

declare(strict_types=1);

namespace Mk\Form\Fields;

use Mk\Form\FormField;

/**
 * Select Field
 *
 * A select dropdown field with options.
 */
class SelectField extends FormField
{
    /**
     * Create a new select field.
     */
    public static function make(string $name): self
    {
        $instance = parent::make($name);
        $instance->type('select');
        return $instance;
    }

    /**
     * Allow multiple selections.
     */
    public function multiple(bool $multiple = true): self
    {
        $this->props['multiple'] = $multiple;
        return $this;
    }

    /**
     * Set the placeholder option.
     */
    public function placeholder(string $placeholder): self
    {
        $this->props['placeholder'] = $placeholder;
        return $this;
    }
}
