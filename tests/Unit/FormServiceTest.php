<?php

declare(strict_types=1);

namespace Mk\Form\Tests\Unit;

use Mk\Form\Contracts\FormContract;
use Mk\Form\Form;
use Mk\Form\Services\FormService;
use PHPUnit\Framework\TestCase;

class FormServiceTest extends TestCase
{
    protected FormService $formService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->formService = new FormService();
    }

    public function test_register_form_by_instance(): void
    {
        $form = new TestFormA();
        $this->formService->registerForm($form);

        $this->assertTrue($this->formService->hasForm('test-form-a'));
        $this->assertSame($form, $this->formService->getForm('test-form-a'));
    }

    public function test_register_form_by_class_name(): void
    {
        $this->formService->registerFormByClass(TestFormA::class);

        $this->assertTrue($this->formService->hasForm('test-form-a'));
        $this->assertInstanceOf(TestFormA::class, $this->formService->getForm('test-form-a'));
    }

    public function test_register_form_by_class_throws_exception_for_non_existent_class(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Form class NonExistentForm does not exist.');

        $this->formService->registerFormByClass('NonExistentForm');
    }

    public function test_register_form_by_class_throws_exception_for_non_form_class(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('must implement FormContract');

        $this->formService->registerFormByClass(\stdClass::class);
    }

    public function test_register_multiple_forms_at_once(): void
    {
        $this->formService->registerForms([
            TestFormA::class,
            new TestFormB(),
            TestFormC::class,
        ]);

        $this->assertTrue($this->formService->hasForm('test-form-a'));
        $this->assertTrue($this->formService->hasForm('test-form-b'));
        $this->assertTrue($this->formService->hasForm('test-form-c'));
    }

    public function test_register_forms_throws_exception_for_invalid_type(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Each form must be either a FormContract instance or a class name string');

        $this->formService->registerForms([
            TestFormA::class,
            123, // Invalid type
        ]);
    }

    public function test_register_forms_from_namespace(): void
    {
        $testDir = sys_get_temp_dir() . '/form_test_' . uniqid();
        mkdir($testDir);

        // Create test form files
        file_put_contents($testDir . '/DynamicFormA.php', '<?php
namespace TestNamespace;
use Mk\Form\Form;
class DynamicFormA extends Form {
    public function configure(): void {
        $this->setName("dynamic-form-a");
    }
    public function fields(): array { return []; }
    public function handle(mixed $request): mixed { return []; }
}');

        file_put_contents($testDir . '/DynamicFormB.php', '<?php
namespace TestNamespace;
use Mk\Form\Form;
class DynamicFormB extends Form {
    public function configure(): void {
        $this->setName("dynamic-form-b");
    }
    public function fields(): array { return []; }
    public function handle(mixed $request): mixed { return []; }
}');

        // Include the files
        require_once $testDir . '/DynamicFormA.php';
        require_once $testDir . '/DynamicFormB.php';

        $this->formService->registerFormsFromNamespace('TestNamespace', $testDir);

        $this->assertTrue($this->formService->hasForm('dynamic-form-a'));
        $this->assertTrue($this->formService->hasForm('dynamic-form-b'));

        // Cleanup
        unlink($testDir . '/DynamicFormA.php');
        unlink($testDir . '/DynamicFormB.php');
        rmdir($testDir);
    }

    public function test_register_forms_from_namespace_throws_exception_for_invalid_directory(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Directory /non/existent/path does not exist');

        $this->formService->registerFormsFromNamespace('App\\Forms', '/non/existent/path');
    }

    public function test_get_form_throws_exception_for_non_existent_form(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Form non-existent not found');

        $this->formService->getForm('non-existent');
    }

    public function test_has_form_returns_false_for_non_existent_form(): void
    {
        $this->assertFalse($this->formService->hasForm('non-existent'));
    }

    public function test_get_all_forms(): void
    {
        $this->formService->registerForms([
            TestFormA::class,
            TestFormB::class,
        ]);

        $allForms = $this->formService->getAllForms();

        $this->assertIsArray($allForms);
        $this->assertCount(2, $allForms);
        $this->assertArrayHasKey('test-form-a', $allForms);
        $this->assertArrayHasKey('test-form-b', $allForms);
    }

    public function test_clear_forms(): void
    {
        $this->formService->registerForm(new TestFormA());
        $this->assertTrue($this->formService->hasForm('test-form-a'));

        $this->formService->clearForms();
        $this->assertFalse($this->formService->hasForm('test-form-a'));
    }

    public function test_validate_form_submission_with_valid_data(): void
    {
        $form = new TestFormWithValidation();
        $this->formService->registerForm($form);

        $errors = $this->formService->validateFormSubmission($form, [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $this->assertEmpty($errors);
    }

    public function test_validate_form_submission_with_invalid_data(): void
    {
        $form = new TestFormWithValidation();
        $this->formService->registerForm($form);

        $errors = $this->formService->validateFormSubmission($form, [
            'name' => '',
            'email' => 'invalid-email',
        ]);

        $this->assertNotEmpty($errors);
        $this->assertArrayHasKey('name', $errors);
        $this->assertArrayHasKey('email', $errors);
    }
}

// Test form classes
class TestFormA extends Form
{
    public function configure(): void
    {
        $this->setName('test-form-a');
    }

    public function fields(): array
    {
        return [];
    }

    public function handle(mixed $request): mixed
    {
        return ['success' => true];
    }
}

class TestFormB extends Form
{
    public function configure(): void
    {
        $this->setName('test-form-b');
    }

    public function fields(): array
    {
        return [];
    }

    public function handle(mixed $request): mixed
    {
        return ['success' => true];
    }
}

class TestFormC extends Form
{
    public function configure(): void
    {
        $this->setName('test-form-c');
    }

    public function fields(): array
    {
        return [];
    }

    public function handle(mixed $request): mixed
    {
        return ['success' => true];
    }
}

class TestFormWithValidation extends Form
{
    public function configure(): void
    {
        $this->setName('test-form-validation');
    }

    public function fields(): array
    {
        return [];
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3'],
            'email' => ['required', 'email'],
        ];
    }

    public function handle(mixed $request): mixed
    {
        return ['success' => true];
    }
}
