<?php

declare(strict_types=1);

namespace Mk\Form\Tests\Unit;

use Illuminate\Http\Request;
use Mk\Form\Form;
use Mk\Form\Fields\TextInputField;
use Mk\Form\Fields\TextareaField;
use PHPUnit\Framework\TestCase;

/**
 * Test the Form class.
 */
class FormTest extends TestCase
{
    /**
     * Test form creation and basic properties.
     */
    public function testFormCreation(): void
    {
        $form = new TestForm();
        $formArray = $form->toArray();

        $this->assertEquals('Test Form', $form->getTitle());
        $this->assertEquals('/forms/test', $form->getEndpoint());
        $this->assertEquals('POST', $form->getMethod());
        $this->assertArrayHasKey('title', $formArray);
        $this->assertArrayHasKey('endpoint', $formArray);
        $this->assertArrayHasKey('method', $formArray);
        $this->assertArrayHasKey('fields', $formArray);
    }

    /**
     * Test form configuration.
     */
    public function testFormConfiguration(): void
    {
        $form = new TestForm();
        $config = $form->getConfiguration();

        $this->assertArrayHasKey('width', $config);
        $this->assertEquals('max-w-2xl', $config['width']);
        $this->assertArrayHasKey('submitLabel', $config);
        $this->assertEquals('Submit Form', $config['submitLabel']);
    }

    /**
     * Test form validation rules.
     */
    public function testFormRules(): void
    {
        $form = new TestForm();
        $rules = $form->rules();

        $this->assertArrayHasKey('name', $rules);
        $this->assertArrayHasKey('description', $rules);
        $this->assertContains('required', $rules['name']);
        $this->assertContains('required', $rules['description']);
    }

    /**
     * Test form handling.
     */
    public function testFormHandling(): void
    {
        $form = new TestForm();
        $request = new Request(['name' => 'Test', 'description' => 'Test description']);

        $result = $form->handle($request);

        $this->assertEquals('Form handled successfully', $result);
    }
}

/**
 * Test form implementation.
 */
class TestForm extends Form
{
    protected function configure(): void
    {
        $this->setTitle('Test Form')
            ->setConfiguration([
                'width' => 'max-w-2xl',
                'submitLabel' => 'Submit Form',
            ]);
    }

    public function fields(): array
    {
        return [
            TextInputField::make('name')
                ->setLabel('Name')
                ->setRequired(true)
                ->rules(['required', 'string', 'max:255']),

            TextareaField::make('description')
                ->setLabel('Description')
                ->setRequired(true)
                ->rules(['required', 'string']),
        ];
    }

    public function handle(Request $request): mixed
    {
        return 'Form handled successfully';
    }
}
