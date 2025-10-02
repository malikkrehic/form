<?php

declare(strict_types=1);

namespace Mk\Form\Fields;

use Mk\Form\FormField;

/**
 * Date Field
 *
 * A date input field with min/max date validation.
 */
class DateField extends FormField
{
    /**
     * Create a new date field.
     */
    public static function make(string $name): self
    {
        $instance = parent::make($name);
        $instance->type('date');
        return $instance;
    }

    /**
     * Set the minimum date.
     */
    public function min(string $min): self
    {
        $this->props['min'] = $min;
        $this->rules[] = "after_or_equal:{$min}";
        return $this;
    }

    /**
     * Set the maximum date.
     */
    public function max(string $max): self
    {
        $this->props['max'] = $max;
        $this->rules[] = "before_or_equal:{$max}";
        return $this;
    }

    /**
     * Set the date format for validation.
     */
    public function format(string $format): self
    {
        $this->rules[] = "date_format:{$format}";
        return $this;
    }

    /**
     * Require the date to be after a specific date.
     */
    public function after(string $date): self
    {
        $this->rules[] = "after:{$date}";
        return $this;
    }

    /**
     * Require the date to be before a specific date.
     */
    public function before(string $date): self
    {
        $this->rules[] = "before:{$date}";
        return $this;
    }
}
