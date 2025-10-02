<?php

declare(strict_types=1);

namespace Mk\Form\Tests\Unit;

use PHPUnit\Framework\TestCase;

class FormServiceProviderPublishTest extends TestCase
{
    public function test_form_service_provider_stub_exists(): void
    {
        $stubPath = __DIR__ . '/../../stubs/FormServiceProvider.stub';
        
        $this->assertFileExists($stubPath, 'FormServiceProvider stub file should exist');
    }

    public function test_form_service_provider_stub_has_valid_php_syntax(): void
    {
        $stubPath = __DIR__ . '/../../stubs/FormServiceProvider.stub';
        $content = file_get_contents($stubPath);

        // Check for basic PHP structure
        $this->assertStringContainsString('<?php', $content);
        $this->assertStringContainsString('namespace App\Providers;', $content);
        $this->assertStringContainsString('class FormServiceProvider extends ServiceProvider', $content);
        $this->assertStringContainsString('public function boot(): void', $content);
        $this->assertStringContainsString('protected function registerForms(): void', $content);
    }

    public function test_form_service_provider_stub_imports_required_classes(): void
    {
        $stubPath = __DIR__ . '/../../stubs/FormServiceProvider.stub';
        $content = file_get_contents($stubPath);

        $this->assertStringContainsString('use Illuminate\Support\ServiceProvider;', $content);
        $this->assertStringContainsString('use Mk\Form\Support\FormRegistrar;', $content);
        $this->assertStringContainsString('use Mk\Form\Services\FormService;', $content);
    }

    public function test_form_service_provider_stub_has_registration_examples(): void
    {
        $stubPath = __DIR__ . '/../../stubs/FormServiceProvider.stub';
        $content = file_get_contents($stubPath);

        // Check for different registration method examples
        $this->assertStringContainsString('FormRegistrar::make', $content);
        $this->assertStringContainsString('registerForms', $content);
        $this->assertStringContainsString('registerFormsFromNamespace', $content);
    }

    public function test_form_service_provider_stub_has_strict_types(): void
    {
        $stubPath = __DIR__ . '/../../stubs/FormServiceProvider.stub';
        $content = file_get_contents($stubPath);

        $this->assertStringContainsString('declare(strict_types=1);', $content);
    }
}
