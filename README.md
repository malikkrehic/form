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

### Step 2: Publish Configuration (Optional)

```bash
php artisan vendor:publish --provider="Mk\Form\FormServiceProvider" --tag="form-config"
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

class ContactForm extends Form
{
    protected function configure(): void
    {
        $this->setTitle('Contact Us')
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

Register your form in a service provider:

```php
use Mk\Form\Services\FormService;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $formService = app(FormService::class);
        $formService->registerForm(new ContactForm());
    }
}
```

Or register by class name:

```php
$formService->registerFormByClass(ContactForm::class);
```

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

Enable auto-registration in your configuration to automatically register forms from directories:

```php
// config/form.php
'auto_register' => true,
'directories' => [
    app_path('Forms'),
    app_path('Http/Forms'),
],
```

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
- Laravel 11.0+
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
