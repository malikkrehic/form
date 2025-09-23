<?php

declare(strict_types=1);

namespace Mk\Form\Tests\Unit;

use Illuminate\Http\Request;
use Mk\Form\Fields\TextInputField;
use Mk\Form\Form;
use Mk\Form\Services\FormService;
use PHPUnit\Framework\TestCase;

/**
 * Test the FormService class.
 */
class FormServiceTest extends TestCase
{
    private FormService $formService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->formService = new FormService();
    }

    /**
     * Test form registration.
     */
    public function testFormRegistration(): void
    {
        $form = new TestForm();
        $this->formService->registerForm($form);

        $this->assertTrue($this->formService->hasForm('test'));
        $this->assertInstanceOf(Form::class, $this->formService->getForm('test'));
    }

    /**
     * Test form registration by class name.
     */
    public function testFormRegistrationByClass(): void
    {
        $this->formService->registerFormByClass(TestForm::class);

        $this->assertTrue($this->formService->hasForm('test'));
        $this->assertInstanceOf(TestForm::class, $this->formService->getForm('test'));
    }

    /**
     * Test getting all forms.
     */
    public function testGetAllForms(): void
    {
        $form1 = new TestForm();
        $form2 = new AnotherTestForm();

        $this->formService->registerForm($form1);
        $this->formService->registerForm($form2);

        $allForms = $this->formService->getAllForms();

        $this->assertCount(2, $allForms);
        $this->assertArrayHasKey('test', $allForms);
        $this->assertArrayHasKey('another-test', $allForms);
    }

    /**
     * Test form validation.
     */
    public function testFormValidation(): void
    {
        $form = new TestForm();
        $this->formService->registerForm($form);

        $validData = ['name' => 'Test Name', 'description' => 'Test description'];
        $errors = $this->formService->validateFormSubmission($form, $validData);

        $this->assertEmpty($errors);

        $invalidData = ['name' => '', 'description' => ''];
        $errors = $this->formService->validateFormSubmission($form, $invalidData);

        $this->assertNotEmpty($errors);
        $this->assertArrayHasKey('name', $errors);
        $this->assertArrayHasKey('description', $errors);
    }

    /**
     * Test form processing.
     */
    public function testFormProcessing(): void
    {
        $form = new TestForm();
        $this->formService->registerForm($form);

        $request = new Request(['name' => 'Test', 'description' => 'Test description']);
        $result = $this->formService->processFormSubmission('test', $request);

        $this->assertTrue($result['success']);
        $this->assertEquals('Form handled successfully', $result['result']);
    }

    /**
     * Test form not found exception.
     */
    public function testFormNotFound(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Form notfound not found');

        $this->formService->getForm('notfound');
    }
}

/**
 * Test form implementation.
 */
class TestForm extends Form
{
    protected function configure(): void
    {
        $this->setTitle('Test Form');
    }

    public function fields(): array
    {
        return [
            TextInputField::make('name')
                ->setLabel('Name')
                ->setRequired(true),
        ];
    }

    public function handle(Request $request): mixed
    {
        return 'Form handled successfully';
    }
}

/**
 * Another test form implementation.
 */
class AnotherTestForm extends Form
{
    protected function configure(): void
    {
        $this->setTitle('Another Test Form');
    }

    public function fields(): array
    {
        return [
            TextInputField::make('email')
                ->setLabel('Email')
                ->setRequired(true),
        ];
    }

    public function handle(Request $request): mixed
    {
        return 'Another form handled successfully';
    }
}
