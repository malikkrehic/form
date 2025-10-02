<?php

declare(strict_types=1);

namespace Mk\Form\Tests\Unit;

use Mk\Form\Form;
use Mk\Form\Services\FormService;
use Mk\Form\Support\FormRegistrar;
use PHPUnit\Framework\TestCase;

class FormRegistrarTest extends TestCase
{
    protected FormService $formService;
    protected FormRegistrar $registrar;

    protected function setUp(): void
    {
        parent::setUp();
        $this->formService = new FormService();
        $this->registrar = new FormRegistrar($this->formService);
    }

    public function test_can_create_registrar_with_make_method(): void
    {
        $registrar = FormRegistrar::make($this->formService);

        $this->assertInstanceOf(FormRegistrar::class, $registrar);
    }

    public function test_add_form_instance(): void
    {
        $form = new RegistrarTestFormA();

        $this->registrar
            ->add($form)
            ->register();

        $this->assertTrue($this->formService->hasForm('registrar-test-form-a'));
    }

    public function test_add_form_by_class_name(): void
    {
        $this->registrar
            ->addClass(RegistrarTestFormA::class)
            ->register();

        $this->assertTrue($this->formService->hasForm('registrar-test-form-a'));
    }

    public function test_add_many_forms(): void
    {
        $this->registrar
            ->addMany([
                RegistrarTestFormA::class,
                new RegistrarTestFormB(),
                RegistrarTestFormC::class,
            ])
            ->register();

        $this->assertTrue($this->formService->hasForm('registrar-test-form-a'));
        $this->assertTrue($this->formService->hasForm('registrar-test-form-b'));
        $this->assertTrue($this->formService->hasForm('registrar-test-form-c'));
    }

    public function test_fluent_chaining(): void
    {
        $result = $this->registrar
            ->add(new RegistrarTestFormA())
            ->addClass(RegistrarTestFormB::class)
            ->addMany([RegistrarTestFormC::class]);

        $this->assertInstanceOf(FormRegistrar::class, $result);

        $result->register();

        $this->assertTrue($this->formService->hasForm('registrar-test-form-a'));
        $this->assertTrue($this->formService->hasForm('registrar-test-form-b'));
        $this->assertTrue($this->formService->hasForm('registrar-test-form-c'));
    }

    public function test_from_namespace(): void
    {
        $testDir = sys_get_temp_dir() . '/form_registrar_test_' . uniqid();
        mkdir($testDir);

        // Create test form file
        file_put_contents($testDir . '/NamespaceTestForm.php', '<?php
namespace RegistrarTestNamespace;
use Mk\Form\Form;
class NamespaceTestForm extends Form {
    public function configure(): void {
        $this->setName("namespace-test-form");
    }
    public function fields(): array { return []; }
    public function handle(mixed $request): mixed { return []; }
}');

        // Include the file
        require_once $testDir . '/NamespaceTestForm.php';

        $this->registrar->fromNamespace('RegistrarTestNamespace', $testDir);

        $this->assertTrue($this->formService->hasForm('namespace-test-form'));

        // Cleanup
        unlink($testDir . '/NamespaceTestForm.php');
        rmdir($testDir);
    }

    public function test_register_clears_queue(): void
    {
        $this->registrar
            ->addClass(RegistrarTestFormA::class)
            ->register();

        // Register again - should not throw error about duplicate
        $this->registrar
            ->addClass(RegistrarTestFormB::class)
            ->register();

        $this->assertTrue($this->formService->hasForm('registrar-test-form-a'));
        $this->assertTrue($this->formService->hasForm('registrar-test-form-b'));
    }
}

// Test form classes
class RegistrarTestFormA extends Form
{
    public function configure(): void
    {
        $this->setName('registrar-test-form-a');
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

class RegistrarTestFormB extends Form
{
    public function configure(): void
    {
        $this->setName('registrar-test-form-b');
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

class RegistrarTestFormC extends Form
{
    public function configure(): void
    {
        $this->setName('registrar-test-form-c');
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
