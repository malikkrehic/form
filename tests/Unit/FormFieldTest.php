<?php

declare(strict_types=1);

namespace Mk\Form\Tests\Unit;

use Mk\Form\Fields\TextInputField;
use Mk\Form\Fields\TextareaField;
use Mk\Form\Fields\SelectField;
use PHPUnit\Framework\TestCase;

/**
 * Test the FormField classes.
 */
class FormFieldTest extends TestCase
{
    /**
     * Test TextInputField creation.
     */
    public function testTextInputFieldCreation(): void
    {
        $field = TextInputField::make('username')
            ->setLabel('Username')
            ->setRequired(true)
            ->setPlaceholder('Enter your username');

        $fieldArray = $field->toArray();

        $this->assertEquals('username', $field->name);
        $this->assertEquals('Username', $field->label);
        $this->assertEquals('text', $field->type);
        $this->assertTrue($field->required);
        $this->assertEquals('Enter your username', $field->placeholder);
        $this->assertArrayHasKey('rules', $fieldArray);
    }

    /**
     * Test TextareaField creation.
     */
    public function testTextareaFieldCreation(): void
    {
        $field = TextareaField::make('description')
            ->setLabel('Description')
            ->rows(5)
            ->setPlaceholder('Enter description');

        $fieldArray = $field->toArray();

        $this->assertEquals('description', $field->name);
        $this->assertEquals('Description', $field->label);
        $this->assertEquals('textarea', $field->type);
        $this->assertEquals('Enter description', $field->placeholder);
        $this->assertEquals(5, $field->props['rows']);
    }

    /**
     * Test SelectField creation.
     */
    public function testSelectFieldCreation(): void
    {
        $options = [
            ['label' => 'Option 1', 'value' => 'option1'],
            ['label' => 'Option 2', 'value' => 'option2'],
        ];

        $field = SelectField::make('type')
            ->setLabel('Type')
            ->setOptions($options)
            ->setDefaultValue('option1');

        $fieldArray = $field->toArray();

        $this->assertEquals('type', $field->name);
        $this->assertEquals('Type', $field->label);
        $this->assertEquals('select', $field->type);
        $this->assertEquals('option1', $field->defaultValue);
        $this->assertEquals($options, $field->options);
    }

    /**
     * Test field validation rules.
     */
    public function testFieldRules(): void
    {
        $field = TextInputField::make('email')
            ->setRequired(true)
            ->rules(['email', 'unique:users,email']);

        $rules = $field->rules;

        $this->assertTrue($field->required);
        $this->assertContains('required', $rules);
        $this->assertContains('email', $rules);
        $this->assertContains('unique:users,email', $rules);
    }

    /**
     * Test field transformers.
     */
    public function testFieldTransformers(): void
    {
        $field = TextInputField::make('name')
            ->addTransformer('strtoupper')
            ->addTransformer(fn($value) => trim($value));

        $transformed = $field->applyTransformers('  john doe  ');

        $this->assertEquals('JOHN DOE', $transformed);
    }

    /**
     * Test magic getter.
     */
    public function testMagicGetter(): void
    {
        $field = TextInputField::make('test')
            ->setLabel('Test Label')
            ->setPlaceholder('Test Placeholder');

        $this->assertEquals('test', $field->name);
        $this->assertEquals('Test Label', $field->label);
        $this->assertEquals('Test Placeholder', $field->placeholder);
    }
}
