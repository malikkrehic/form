<?php

declare(strict_types=1);

namespace Mk\Form;

use Illuminate\Support\ServiceProvider;
use Mk\Form\Controllers\FormController;
use Mk\Form\Services\FormService;

/**
 * Form Service Provider
 *
 * Registers the form package services, routes, and configurations.
 */
class FormServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(FormService::class, function ($app) {
            return new FormService();
        });

        // Register the facade
        $this->app->alias(FormService::class, 'mk-form');

        // Merge default package configuration in register per Laravel best practices
        $this->mergeConfigFrom(
            __DIR__.'/../config/form.php', 'form'
        );
    }

    /**
     * Bootstrap any application services.
     */
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerRoutes();

        // Publish configuration file
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/form.php' => $this->app->configPath('form.php'),
            ], 'form-config');
        }

        if ($this->app['config']->get('form.auto_register', false)) {
            $this->autoRegisterForms();
        }
    }

    /**
     * Auto-register forms from configured directories.
     */
    protected function autoRegisterForms(): void
    {
        $directories = $this->app['config']->get('form.directories', []);
        $formService = $this->app->make(FormService::class);

        foreach ($directories as $directory) {
            // Convert config paths to full paths using Laravel's app_path helper
            $fullPath = app_path($directory);
            if (is_dir($fullPath)) {
                $this->registerFormsInDirectory($fullPath, $formService);
            }
        }
    }

    /**
     * Register forms in a specific directory.
     */
    protected function registerFormsInDirectory(string $directory, FormService $formService): void
    {
        $files = glob($directory . '/*Form.php');

        foreach ($files as $file) {
            $className = $this->getClassNameFromFile($file);

            if ($className && class_exists($className)) {
                try {
                    $formService->registerFormByClass($className);
                } catch (\InvalidArgumentException $e) {
                    // Skip forms that don't implement FormContract
                    continue;
                }
            }
        }
    }

    /**
     * Extract class name from a PHP file.
     */
    protected function getClassNameFromFile(string $file): ?string
    {
        $content = file_get_contents($file);
        $namespace = '';

        if (preg_match('/^namespace\s+(.+?);/m', $content, $matches)) {
            $namespace = $matches[1];
        }

        if (preg_match('/^class\s+(\w+)/m', $content, $matches)) {
            return $namespace ? $namespace . '\\' . $matches[1] : $matches[1];
        }

        return null;
    }

    /**
     * Register the form routes.
     */
    protected function registerRoutes(): void
    {
        $router = $this->app['router'];
        
        if (!$router->getRoutes()->hasNamedRoute('forms.list')) {
            $router->group([
                'prefix' => 'forms',
                'as' => 'forms.',
                'middleware' => ['web'],
            ], function ($router) {
                $router->get('/list', [FormController::class, 'index'])->name('list');
                $router->get('/{formName}', [FormController::class, 'getForm'])->name('get');
            });
        }
    }
}
