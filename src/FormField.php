<?php

declare(strict_types=1);

namespace Mk\Form;

use InvalidArgumentException;
use ReflectionClass;
use Mk\Form\Contracts\FormFieldContract;

/**
 * Abstract Form Field Class
 *
 * Base class for all form fields. Provides fluent API for field configuration
 * and handles field properties, validation, and data transformation.
 */
abstract class FormField implements FormFieldContract
{
    /**
     * Core properties
     */
    protected string $name = '';
    protected string $label = '';
    protected string $type = 'text';
    protected bool $required = false;
    protected array $rules = [];
    protected array $options = [];
    protected mixed $defaultValue = null;
    protected ?string $placeholder = null;
    protected array $attributes = [];
    protected ?string $helpText = null;

    /**
     * Vue or custom component support
     */
    protected ?string $component = null;
    protected array $props = [];

    /**
     * Transformers for incoming value
     */
    protected array $transformers = [];

    /**
     * Create a new field instance.
     */
    public function __construct()
    {
        $this->props = [];
        $this->rules = [];
        $this->options = [];
        $this->attributes = [];
        $this->transformers = [];
    }

    /**
     * Static constructor.
     */
    public static function make(string $name): self
    {
        $instance = new static();
        $instance->name = $name;
        $instance->label = ucfirst($name); // Set a default label based on name
        return $instance;
    }

    /**
     * Set the field name.
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Set the field label.
     */
    public function setLabel(string $label): self
    {
        $this->label = $label;
        return $this;
    }

    /**
     * Set the field type.
     */
    public function type(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Set whether the field is required.
     */
    public function setRequired(bool $required = true): self
    {
        $this->required = $required;
        return $this;
    }

    /**
     * Set the validation rules.
     */
    public function rules(array $rules): self
    {
        $this->rules = $rules;
        return $this;
    }

    /**
     * Handle options as array, associative array, or enum class string.
     */
    public function setOptions(array|string $options): self
    {
        // If $options is a string, check if it's an Enum class
        if (is_string($options)) {
            if (!class_exists($options)) {
                throw new InvalidArgumentException("The class {$options} does not exist.");
            }

            $reflection = new ReflectionClass($options);
            if ($reflection->isEnum()) {
                $this->options = array_map(
                    fn($case) => ['label' => $case->name, 'value' => $case->value],
                    $options::cases()
                );
            } else {
                throw new InvalidArgumentException("The class {$options} is not an enum.");
            }

            return $this;
        }

        // If $options is an associative array, convert to [ [label => ..., value => ...], ... ]
        if (!empty($options) && !is_numeric(array_key_first($options))) {
            $this->options = array_map(
                fn($value, $key) => ['label' => $value, 'value' => $key],
                $options,
                array_keys($options)
            );
        } else {
            $this->options = $options;
        }

        return $this;
    }

    /**
     * Set the default value.
     */
    public function setDefaultValue(mixed $value): self
    {
        $this->defaultValue = $value;
        return $this;
    }

    /**
     * Set the placeholder text.
     */
    public function setPlaceholder(string $placeholder): self
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    /**
     * Set help text.
     */
    public function setHelpText(string $helpText): self
    {
        $this->helpText = $helpText;
        return $this;
    }

    /**
     * Set help text (alias for setHelpText).
     */
    public function setHelp(string $helpText): self
    {
        return $this->setHelpText($helpText);
    }

    /**
     * Set a custom component.
     */
    public function component(string $component): self
    {
        $this->component = $component;
        return $this;
    }

    /**
     * Set additional props.
     */
    public function props(array $props): self
    {
        $this->props = array_merge($this->props, $props);
        return $this;
    }

    /**
     * Add a transformer for the field value.
     */
    public function addTransformer(callable $transformer): self
    {
        $this->transformers[] = $transformer;
        return $this;
    }

    /**
     * Apply transformers to a value.
     */
    public function applyTransformers(mixed $value): mixed
    {
        foreach ($this->transformers as $transformer) {
            $value = $transformer($value);
        }
        return $value;
    }

    /**
     * Convert the field to an array representation.
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'label' => $this->label ?: ucfirst($this->name),
            'required' => $this->required,
            'rules' => $this->rules,
            'options' => $this->options,
            'defaultValue' => $this->defaultValue,
            'component' => $this->component,
            'props' => $this->props,
            'placeholder' => $this->placeholder,
            'attributes' => $this->attributes,
            'helpText' => $this->helpText,
        ];
    }

    /**
     * Magic getter to allow $field->name, $field->props, etc.
     */
    public function __get(string $key): mixed
    {
        return $this->toArray()[$key] ?? null;
    }
}
