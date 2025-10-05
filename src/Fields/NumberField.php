<?php

declare(strict_types=1);

namespace Mk\Form\Fields;

use Mk\Form\FormField;

/**
 * Number Field
 *
 * A number input field with min/max/step validation and decimal support.
 *
 * @method self setStep(string|float $step) Set the step value for increments
 * @method self setMin(float $min) Set the minimum value
 * @method self setMax(float $max) Set the maximum value
 * @method self decimal(int $precision = 2) Set as decimal with precision
 * @method self integer() Set as integer only
 * @method self positive() Accept only positive numbers
 * @method self between(float $min, float $max) Set min/max range
 */
class NumberField extends FormField
{
    /**
     * Create a new number field.
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
    public function setMin(float $min): self
    {
        $this->props['min'] = $min;
        $this->rules[] = "min:{$min}";
        return $this;
    }

    /**
     * Set the maximum value.
     */
    public function setMax(float $max): self
    {
        $this->props['max'] = $max;
        $this->rules[] = "max:{$max}";
        return $this;
    }

    /**
     * Set the step value for increments.
     */
    public function setStep(string|float $step): self
    {
        $this->props['step'] = $step;
        return $this;
    }

    /**
     * Set the field as a decimal number with specific precision.
     */
    public function decimal(int $precision = 2): self
    {
        $this->rules[] = "numeric";
        $this->rules[] = "decimal:0,{$precision}";
        $this->setStep((string) (1 / pow(10, $precision)));
        return $this;
    }

    /**
     * Set the field as an integer.
     */
    public function integer(): self
    {
        $this->rules[] = "integer";
        $this->setStep('1');
        return $this;
    }

    /**
     * Set the field to only accept positive numbers.
     */
    public function positive(): self
    {
        $this->setMin(0);
        return $this;
    }

    /**
     * Set a range for the number field.
     */
    public function between(float $min, float $max): self
    {
        $this->setMin($min);
        $this->setMax($max);
        return $this;
    }
}
