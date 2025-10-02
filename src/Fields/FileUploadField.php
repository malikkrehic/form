<?php

declare(strict_types=1);

namespace Mk\Form\Fields;

use Mk\Form\FormField;

/**
 * File Upload Field
 *
 * A file upload field for handling file uploads.
 */
class FileUploadField extends FormField
{
    /**
     * Create a new file upload field.
     */
    public static function make(string $name): self
    {
        $instance = parent::make($name);
        $instance->type('file');
        return $instance;
    }

    /**
     * Set accepted file types.
     */
    public function accept(string $accept): self
    {
        $this->props['accept'] = $accept;
        return $this;
    }

    /**
     * Allow multiple file selection.
     */
    public function multiple(bool $multiple = true): self
    {
        $this->props['multiple'] = $multiple;
        return $this;
    }

    /**
     * Set maximum file size in bytes.
     */
    public function maxSize(int $maxSize): self
    {
        $this->props['maxSize'] = $maxSize;
        return $this;
    }

    /**
     * Set configuration options for the file upload field.
     */
    public function setConfiguration(array $config): self
    {
        if (isset($config['accept'])) {
            $this->accept($config['accept']);
        }
        if (isset($config['maxFileSize'])) {
            $this->maxSize($config['maxFileSize'] * 1024); // Convert KB to bytes
        }
        if (isset($config['multiple'])) {
            $this->multiple($config['multiple']);
        }
        return $this;
    }
}
