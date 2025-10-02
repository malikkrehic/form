# MK Form Package

A powerful Laravel form backend system with fluent API, validation, and HTTP endpoints for building dynamic forms in your applications.

## Features

- 🚀 **Fluent API**: Easy-to-use fluent interface for defining forms and fields
- 🔧 **Flexible Fields**: Support for various field types (text, textarea, select, checkbox, etc.)
- ✅ **Built-in Validation**: Automatic validation rule generation from field definitions
- 🔌 **RESTful Endpoints**: HTTP endpoints for listing and retrieving forms
- 🎯 **Type Safety**: Full type hints and strict typing throughout
- 📦 **PSR-4 Compliant**: Follows PHP standards for autoloading
- 🧪 **Well Tested**: Comprehensive test suite included

## Installation

### Step 1: Install via Composer

```bash
composer require mk/form
```

### Step 2: Publish Assets (Optional)

Publish the configuration file:

```bash
php artisan vendor:publish --provider="Mk\Form\FormServiceProvider" --tag="form-config"
```

Publish a dedicated FormServiceProvider (recommended for larger apps):

```bash
php artisan vendor:publish --provider="Mk\Form\FormServiceProvider" --tag="form-provider"
```

Or publish everything:

```bash
php artisan vendor:publish --provider="Mk\Form\FormServiceProvider"
```

### Step 3: Register Forms

Create your form classes that extend `Mk\Form\Form`:

```php
<?php

namespace App\Forms;

use Mk\Form\Form;
use Mk\Form\Fields\TextInputField;
use Mk\Form\Fields\TextareaField;
use Mk\Form\Fields\SelectField;
use Mk\Form\Fields\DateField;

class ContactForm extends Form
{
    protected function configure(): void
    {
        $this->setName('contact')  // Optional: defaults to kebab-case class name
             ->setTitle('Contact Us')
             ->setConfiguration([
                 'width' => 'max-w-2xl',
                 'submitLabel' => 'Send Message'
             ]);
    }

    public function fields(): array
    {
        return [
            TextInputField::make('name')
                ->setLabel('Full Name')
                ->setRequired(true)
                ->setPlaceholder('Enter your full name'),

            TextInputField::make('email')
                ->setLabel('Email Address')
                ->setRequired(true)
                ->setPlaceholder('Enter your email'),

            SelectField::make('subject')
                ->setLabel('Subject')
                ->setRequired(true)
                ->setOptions([
                    ['label' => 'General Inquiry', 'value' => 'general'],
                    ['label' => 'Support', 'value' => 'support'],
                    ['label' => 'Billing', 'value' => 'billing'],
                ]),

            TextareaField::make('message')
                ->setLabel('Message')
                ->setRequired(true)
                ->setPlaceholder('Enter your message')
                ->rows(5),
        ];
    }

    public function handle(Request $request)
    {
        // Handle form submission
        // $request contains validated data
        return ['success' => true, 'message' => 'Message sent successfully!'];
    }
}
```

## Form Registration

The package provides **multiple convenient ways** to register your forms:

### Method 1: Config File (Recommended)

Add your forms to `config/form.php`:

```php
'forms' => [
    App\Forms\ContactForm::class,
    App\Forms\RegistrationForm::class,
    App\Forms\CreateStoreForm::class,
],
```

Forms listed here are automatically registered when the package boots.

### Method 2: Fluent Registrar (Clean & Chainable)

Use the `FormRegistrar` helper for a fluent registration experience:

```php
use Mk\Form\Support\FormRegistrar;
use Mk\Form\Services\FormService;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        FormRegistrar::make(app(FormService::class))
            ->addClass(ContactForm::class)
            ->addClass(RegistrationForm::class)
            ->addClass(CreateStoreForm::class)
            ->register();
    }
}
```

### Method 3: Bulk Registration

Register multiple forms at once:

```php
use Mk\Form\Services\FormService;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        app(FormService::class)->registerForms([
            ContactForm::class,
            RegistrationForm::class,
            CreateStoreForm::class,
        ]);
    }
}
```

### Method 4: Facade (Quick Access)

Use the Form facade for quick access:

```php
use Mk\Form\Facades\Form;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Form::registerForms([
            ContactForm::class,
            RegistrationForm::class,
        ]);
    }
}
```

### Method 5: Individual Registration

Register forms one at a time:

```php
use Mk\Form\Services\FormService;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $formService = app(FormService::class);
        
        // By class name
        $formService->registerFormByClass(ContactForm::class);
        
        // By instance
        $formService->registerForm(new ContactForm());
    }
}
```

### Method 6: Namespace Registration

Register all forms from a specific namespace and directory:

```php
use Mk\Form\Services\FormService;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        app(FormService::class)->registerFormsFromNamespace(
            'App\\Forms',
            app_path('Forms')
        );
    }
}
```

### Method 7: Dedicated Service Provider (Recommended for Large Apps)

For better organization, create a dedicated `FormServiceProvider`:

**Step 1:** Publish the service provider stub:

```bash
php artisan vendor:publish --provider="Mk\Form\FormServiceProvider" --tag="form-provider"
```

This creates `app/Providers/FormServiceProvider.php`:

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Mk\Form\Support\FormRegistrar;
use Mk\Form\Services\FormService;

class FormServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerForms();
    }

    protected function registerForms(): void
    {
        FormRegistrar::make(app(FormService::class))
            ->addClass(\App\Forms\ContactForm::class)
            ->addClass(\App\Forms\RegistrationForm::class)
            ->addClass(\App\Forms\CreateStoreForm::class)
            ->register();
    }
}
```

**Step 2:** Register the provider in `bootstrap/providers.php` (Laravel 11+):

```php
return [
    App\Providers\AppServiceProvider::class,
    App\Providers\FormServiceProvider::class, // Add this
];
```

Or in `config/app.php` (Laravel 10 and below):

```php
'providers' => [
    // ...
    App\Providers\FormServiceProvider::class,
],
```

**Benefits:**
- Clean separation of concerns
- All form registrations in one place
- Easy to maintain and test
- Doesn't clutter AppServiceProvider

### Quick Comparison

| Method | Best For | Setup Complexity | Maintainability |
|--------|----------|------------------|-----------------|
| Config File | Most apps | Low | ⭐⭐⭐⭐⭐ |
| Dedicated Provider | Large apps | Medium | ⭐⭐⭐⭐⭐ |
| Fluent Registrar | Clean code | Low | ⭐⭐⭐⭐ |
| Bulk Registration | Quick setup | Low | ⭐⭐⭐⭐ |
| Facade | Quick access | Low | ⭐⭐⭐⭐ |
| Individual | Special cases | Low | ⭐⭐⭐ |
| Namespace | Auto-discovery | Medium | ⭐⭐⭐⭐ |

## Usage

### Frontend Integration

The package provides RESTful endpoints for your frontend to consume:

#### List All Forms

```http
GET /forms/list
```

Response:

```json
{
  "forms": {
    "contact": {
      "title": "Contact Us",
      "endpoint": "/forms/contact",
      "method": "POST",
      "configuration": {
        "width": "max-w-2xl",
        "submitLabel": "Send Message"
      },
      "fields": [...]
    }
  },
  "count": 1
}
```

#### Get Form Details

```http
GET /forms/{formName}
```

Response:

```json
{
  "title": "Contact Us",
  "endpoint": "/forms/contact",
  "method": "POST",
  "configuration": {
    "width": "max-w-2xl",
    "submitLabel": "Send Message"
  },
  "fields": [
    {
      "name": "name",
      "type": "text",
      "label": "Full Name",
      "required": true,
      "rules": ["required"],
      "placeholder": "Enter your full name",
      "options": [],
      "defaultValue": null,
      "component": null,
      "props": {},
      "attributes": {},
      "helpText": null
    },
    ...
  ]
}
```

### Form Submission

Submit form data to the form's endpoint:

```http
POST /forms/contact
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "subject": "support",
  "message": "I need help with my order"
}
```

## Available Field Types

### TextInputField

```php
TextInputField::make('username')
    ->setLabel('Username')
    ->setRequired(true)
    ->setPlaceholder('Enter username')
    ->maxLength(50)
    ->minLength(3);
```

### TextareaField

```php
TextareaField::make('description')
    ->setLabel('Description')
    ->rows(5)
    ->setPlaceholder('Enter description');
```

### SelectField

```php
SelectField::make('country')
    ->setLabel('Country')
    ->setOptions([
        ['label' => 'United States', 'value' => 'US'],
        ['label' => 'Canada', 'value' => 'CA'],
    ])
    ->setDefaultValue('US');
```

### CheckboxField

```php
CheckboxField::make('agree_terms')
    ->setLabel('I agree to the terms and conditions')
    ->value(true);
```

### NumberInputField

```php
NumberInputField::make('age')
    ->setLabel('Age')
    ->min(18)
    ->max(100);
```

### DateField

```php
DateField::make('birthdate')
    ->setLabel('Date of Birth')
    ->setRequired(true)
    ->min('1900-01-01')
    ->max('2024-12-31')
    ->format('Y-m-d');
```

### FileUploadField

```php
FileUploadField::make('avatar')
    ->setLabel('Profile Picture')
    ->accept('image/*')
    ->maxSize(2048); // 2MB
```

## Form Configuration

### Basic Configuration

```php
protected function configure(): void
{
    $this->setTitle('My Form')
        ->setConfiguration([
            'width' => 'max-w-2xl',        // CSS width class
            'submitLabel' => 'Submit',     // Submit button text
            'layout' => 'vertical',        // Form layout
            'columns' => 2,                // Number of columns
        ]);
}
```

### Custom Endpoints

```php
protected function configure(): void
{
    $this->setTitle('Custom Form')
        ->setEndpoint('/api/custom-form')
        ->setMethod('PUT');
}
```

## Validation

Validation rules are automatically generated from field definitions:

```php
TextInputField::make('email')
    ->setRequired(true)
    ->rules(['email', 'unique:users,email'])
```

This generates the validation rule: `['email' => ['required', 'email', 'unique:users,email']]`

## Custom Components

You can specify custom Vue components for fields:

```php
TextInputField::make('date')
    ->setLabel('Date')
    ->component('date-picker')
    ->props(['format' => 'YYYY-MM-DD']);
```

## Auto-Registration

Enable auto-registration in your configuration to automatically discover and register forms from directories:

```php
// config/form.php
'auto_register' => true,
'directories' => [
    'app/Forms',
    'app/Http/Forms',
],
```

This will automatically scan the specified directories and register any classes ending with `Form.php` that implement `FormContract`.

## Testing

Run the test suite:

```bash
composer test
```

Run specific test types:

```bash
composer test -- --testsuite=Unit
composer test -- --testsuite=Feature
```

## Configuration Options

```php
// config/form.php
return [
    'defaults' => [
        'method' => 'POST',
        'configuration' => [
            'width' => 'max-w-2xl',
            'submitLabel' => 'Submit',
        ],
    ],

    'routes' => [
        'prefix' => 'forms',
        'middleware' => ['web'],
    ],

    'auto_register' => false,

    'directories' => [
        app_path('Forms'),
    ],
];
```

## Requirements

- PHP 8.3+
- Laravel 12.0+
- Composer

## License

MIT License - see LICENSE file for details.

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Write tests for your changes
4. Ensure all tests pass (`composer test`)
5. Commit your changes (`git commit -m 'Add amazing feature'`)
6. Push to the branch (`git push origin feature/amazing-feature`)
7. Open a Pull Request

## Support

For questions and support, please open an issue on GitHub.

---

Built with ❤️ for Laravel developers
