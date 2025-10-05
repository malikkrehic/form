<?php

declare(strict_types=1);

namespace Mk\Form\Fields;

use Mk\Form\FormField;

/**
 * Checkbox Field
 *
 * A checkbox field for boolean values.
 */
class CheckboxField extends FormField
{
    /**
     * Create a new checkbox field.
     */
    public static function make(string $name): static
    {
        $instance = parent::make($name);
        $instance->type('checkbox');
        return $instance;
    }

    /**
     * Set the checkbox value when checked.
     */
    public function value(mixed $value): self
    {
        $this->props['value'] = $value;
        return $this;
    }
}
