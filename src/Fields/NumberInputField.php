<?php

declare(strict_types=1);

namespace Mk\Form\Fields;

use Mk\Form\FormField;

/**
 * Number Input Field
 *
 * A number input field with min/max/step validation.
 */
class NumberInputField extends FormField
{
    /**
     * Create a new number input field.
     */
    public static function make(string $name): static
    {
        $instance = parent::make($name);
        $instance->type('number');
        return $instance;
    }

    /**
     * Set the minimum value.
     */
    public function min(float $min): self
    {
        $this->props['min'] = $min;
        $this->rules(["min:{$min}"]);
        return $this;
    }

    /**
     * Set the maximum value.
     */
    public function max(float $max): self
    {
        $this->props['max'] = $max;
        $this->rules(["max:{$max}"]);
        return $this;
    }

    /**
     * Set the step value.
     */
    public function step(float $step): self
    {
        $this->props['step'] = $step;
        return $this;
    }
}
