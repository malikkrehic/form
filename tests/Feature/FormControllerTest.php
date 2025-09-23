<?php

declare(strict_types=1);

namespace Mk\Form\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Mk\Form\Fields\TextInputField;
use Mk\Form\Form;
use Mk\Form\Services\FormService;
use Tests\TestCase;

/**
 * Feature test the FormController.
 */
class FormControllerTest extends TestCase
{
    use RefreshDatabase;

    private FormService $formService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->formService = app(FormService::class);
    }

    /**
     * Test listing all forms.
     */
    public function testListForms(): void
    {
        // Register test forms
        $form1 = new TestForm();
        $form2 = new AnotherTestForm();

        $this->formService->registerForm($form1);
        $this->formService->registerForm($form2);

        $response = $this->get('/forms/list');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'forms' => [
                    '*' => [
                        'title',
                        'endpoint',
                        'method',
                        'configuration',
                        'fields'
                    ]
                ],
                'count'
            ]);
    }

    /**
     * Test getting a specific form.
     */
    public function testGetForm(): void
    {
        $form = new TestForm();
        $this->formService->registerForm($form);

        $response = $this->get('/forms/test');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'title',
                'endpoint',
                'method',
                'configuration',
                'fields' => [
                    '*' => [
                        'name',
                        'type',
                        'label',
                        'required',
                        'rules',
                        'options',
                        'defaultValue',
                        'component',
                        'props',
                        'placeholder',
                        'attributes',
                        'helpText'
                    ]
                ]
            ]);
    }

    /**
     * Test getting a non-existent form.
     */
    public function testGetNonExistentForm(): void
    {
        $response = $this->get('/forms/nonexistent');

        $response->assertStatus(404)
            ->assertJsonStructure([
                'error',
                'message'
            ]);
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
                'submitLabel' => 'Submit'
            ]);
    }

    public function fields(): array
    {
        return [
            TextInputField::make('name')
                ->setLabel('Name')
                ->setRequired(true)
                ->setPlaceholder('Enter your name'),
        ];
    }
}

/**
 * Another test form implementation.
 */
class AnotherTestForm extends Form
{
    protected function configure(): void
    {
        $this->setTitle('Another Test Form')
            ->setConfiguration([
                'width' => 'max-w-3xl',
                'submitLabel' => 'Create'
            ]);
    }

    public function fields(): array
    {
        return [
            TextInputField::make('email')
                ->setLabel('Email')
                ->setRequired(true)
                ->setPlaceholder('Enter your email'),
        ];
    }
}
