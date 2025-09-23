<?php

declare(strict_types=1);

namespace Mk\Form;

use Illuminate\Support\Str;
use Mk\Form\Contracts\FormContract;

/**
 * Abstract Form Class
 *
 * Base class for all forms in the system. Provides fluent API for form configuration
 * and handles form registration, validation, and processing.
 */
abstract class Form implements FormContract
{
    protected string $name = '';
    protected string $title = '';
    protected string $endpoint = '';
    protected string $method = 'POST';
    protected array $configuration = [];
    protected array $messages = [];
    protected array $successMessages = [];

    /**
     * Create a new form instance.
     */
    public function __construct()
    {
        $this->setDefaultName();
        $this->setDefaultEndpoint();
        $this->configure();
    }

    /**
     * Configure the form properties.
     * Override this method in your form classes to set up the form.
     */
    abstract public function configure(): void;

    /**
     * Define the form fields.
     * Override this method in your form classes to define the form fields.
     */
    abstract public function fields(): array;

    /**
     * Set the default name based on the form class name.
     */
    protected function setDefaultName(): void
    {
        $className = class_basename($this);
        $this->name = Str::kebab(Str::beforeLast($className, 'Form'));
    }

    /**
     * Set the default endpoint based on the form name.
     */
    protected function setDefaultEndpoint(): void
    {
        $this->endpoint = "/forms/{$this->name}";
    }

    /**
     * Set the form name.
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        // Update endpoint when name changes
        $this->setDefaultEndpoint();
        return $this;
    }

    /**
     * Set the form title.
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Set the form endpoint.
     */
    public function setEndpoint(string $endpoint): self
    {
        $this->endpoint = $endpoint;
        return $this;
    }

    /**
     * Set the form method.
     */
    protected function setMethod(string $method): self
    {
        $this->method = $method;
        return $this;
    }

    /**
     * Set the form configuration.
     */
    protected function setConfiguration(array $configuration): self
    {
        $this->configuration = $configuration;
        return $this;
    }

    /**
     * Get the form name.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the form title.
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Get the form endpoint.
     */
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * Get the form method.
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Get the form configuration.
     */
    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    /**
     * Convert the form to an array representation.
     * This is used by the frontend to build the form.
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'title' => $this->title,
            'endpoint' => $this->endpoint,
            'method' => $this->method,
            'configuration' => $this->configuration,
            'fields' => array_map(
                fn($field) => $field->toArray(),
                $this->fields()
            ),
        ];
    }

    /**
     * Handle the form submission.
     * Override this method in your form classes to handle form submission.
     * @param \Illuminate\Http\Request|array $request The HTTP request or form data
     */
    public function handle(mixed $request): mixed
    {
        return null;
    }

    /**
     * Set validation messages.
     */
    protected function setMessages(array $messages): self
    {
        $this->messages = $messages;
        return $this;
    }

    /**
     * Get validation messages.
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * Get validation rules for all fields.
     */
    public function rules(): array
    {
        $rules = [];
        foreach ($this->fields() as $field) {
            $fieldData = $field->toArray();
            $name = $fieldData['name'] ?? null;
            if ($name !== null) {
                $rules[$name] = $fieldData['rules'] ?? [];
            }
        }
        return $rules;
    }

    /**
     * Set success messages.
     */
    public function setSuccessMessages(array $messages): self
    {
        $this->successMessages = $messages;
        return $this;
    }

    /**
     * Get success messages.
     */
    public function getSuccessMessages(): array
    {
        return $this->successMessages;
    }
}
