<?php

declare(strict_types=1);

use Mk\Form\Fields\TextInputField;
use Mk\Form\Fields\TextareaField;
use Mk\Form\Fields\FileUploadField;

describe('FormField setHelp method', function () {
    it('can set help text using setHelp', function () {
        $field = TextInputField::make('sku')
            ->setHelp('Unique identifier for the design');

        expect($field->helpText)->toBe('Unique identifier for the design');
    });

    it('setHelp returns field instance for chaining', function () {
        $field = TextInputField::make('sku');
        $result = $field->setHelp('Test help text');

        expect($result)->toBe($field);
    });

    it('setHelp is included in array representation', function () {
        $field = TextInputField::make('sku')
            ->setHelp('Unique identifier');

        $array = $field->toArray();

        expect($array)->toHaveKey('helpText');
        expect($array['helpText'])->toBe('Unique identifier');
    });

    it('setHelp works with all field types', function () {
        $textField = TextInputField::make('text')->setHelp('Text help');
        $textareaField = TextareaField::make('textarea')->setHelp('Textarea help');
        $fileField = FileUploadField::make('file')->setHelp('File help');

        expect($textField->helpText)->toBe('Text help');
        expect($textareaField->helpText)->toBe('Textarea help');
        expect($fileField->helpText)->toBe('File help');
    });
});

describe('TextareaField setRows method', function () {
    it('can set rows using setRows', function () {
        $field = TextareaField::make('description')
            ->setRows(3);

        expect($field->props['rows'])->toBe(3);
    });

    it('setRows returns field instance for chaining', function () {
        $field = TextareaField::make('description');
        $result = $field->setRows(5);

        expect($result)->toBe($field);
    });

    it('setRows is included in array representation', function () {
        $field = TextareaField::make('description')
            ->setRows(4);

        $array = $field->toArray();

        expect($array)->toHaveKey('props');
        expect($array['props'])->toHaveKey('rows');
        expect($array['props']['rows'])->toBe(4);
    });

    it('can chain setRows with other methods', function () {
        $field = TextareaField::make('notes')
            ->setLabel('Notes')
            ->setRequired(false)
            ->setPlaceholder('Enter notes')
            ->setRows(2);

        expect($field->label)->toBe('Notes');
        expect($field->required)->toBe(false);
        expect($field->placeholder)->toBe('Enter notes');
        expect($field->props['rows'])->toBe(2);
    });
});

describe('FileUploadField setConfiguration method', function () {
    it('can set accept configuration', function () {
        $field = FileUploadField::make('image')
            ->setConfiguration([
                'accept' => 'image/*',
            ]);

        expect($field->props['accept'])->toBe('image/*');
    });

    it('can set maxFileSize configuration and converts KB to bytes', function () {
        $field = FileUploadField::make('image')
            ->setConfiguration([
                'maxFileSize' => 2048, // 2MB in KB
            ]);

        expect($field->props['maxSize'])->toBe(2048 * 1024); // Should be in bytes
    });

    it('can set multiple configuration', function () {
        $field = FileUploadField::make('images')
            ->setConfiguration([
                'multiple' => true,
            ]);

        expect($field->props['multiple'])->toBe(true);
    });

    it('can set all configurations at once', function () {
        $field = FileUploadField::make('vector')
            ->setConfiguration([
                'accept' => '.ai,.eps,.pdf,.svg',
                'maxFileSize' => 5120, // 5MB in KB
                'multiple' => false,
            ]);

        expect($field->props['accept'])->toBe('.ai,.eps,.pdf,.svg');
        expect($field->props['maxSize'])->toBe(5120 * 1024);
        expect($field->props['multiple'])->toBe(false);
    });

    it('setConfiguration returns field instance for chaining', function () {
        $field = FileUploadField::make('file');
        $result = $field->setConfiguration(['accept' => 'image/*']);

        expect($result)->toBe($field);
    });

    it('can chain setConfiguration with other methods', function () {
        $field = FileUploadField::make('image_url')
            ->setLabel('Design Image')
            ->setRequired(false)
            ->setHelp('Upload a preview image of the design (max 2MB)')
            ->setConfiguration([
                'accept' => 'image/*',
                'maxFileSize' => 2048,
            ]);

        expect($field->label)->toBe('Design Image');
        expect($field->required)->toBe(false);
        expect($field->helpText)->toBe('Upload a preview image of the design (max 2MB)');
        expect($field->props['accept'])->toBe('image/*');
        expect($field->props['maxSize'])->toBe(2048 * 1024);
    });
});

describe('Complete field configuration example', function () {
    it('can configure TextInputField with all methods', function () {
        $field = TextInputField::make('sku')
            ->setLabel('SKU')
            ->setRequired(true)
            ->setPlaceholder('Enter SKU')
            ->setHelp('Unique identifier for the design');

        $array = $field->toArray();

        expect($array['name'])->toBe('sku');
        expect($array['label'])->toBe('SKU');
        expect($array['required'])->toBe(true);
        expect($array['placeholder'])->toBe('Enter SKU');
        expect($array['helpText'])->toBe('Unique identifier for the design');
    });

    it('can configure TextareaField with all methods', function () {
        $field = TextareaField::make('description')
            ->setLabel('Description')
            ->setRequired(false)
            ->setPlaceholder('Enter design description')
            ->setRows(3);

        $array = $field->toArray();

        expect($array['name'])->toBe('description');
        expect($array['label'])->toBe('Description');
        expect($array['required'])->toBe(false);
        expect($array['placeholder'])->toBe('Enter design description');
        expect($array['props']['rows'])->toBe(3);
    });

    it('can configure FileUploadField with all methods', function () {
        $field = FileUploadField::make('image_url')
            ->setLabel('Design Image')
            ->setRequired(false)
            ->setHelp('Upload a preview image of the design (max 2MB)')
            ->setConfiguration([
                'accept' => 'image/*',
                'maxFileSize' => 2048,
            ]);

        $array = $field->toArray();

        expect($array['name'])->toBe('image_url');
        expect($array['label'])->toBe('Design Image');
        expect($array['required'])->toBe(false);
        expect($array['helpText'])->toBe('Upload a preview image of the design (max 2MB)');
        expect($array['props']['accept'])->toBe('image/*');
        expect($array['props']['maxSize'])->toBe(2048 * 1024);
    });
});
