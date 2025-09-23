<?php

declare(strict_types=1);

namespace Mk\Form\Examples;

use Mk\Form\Form;
use Mk\Form\Fields\TextInputField;
use Mk\Form\Fields\TextareaField;
use Mk\Form\Fields\SelectField;
use Mk\Form\Fields\CheckboxField;

/**
 * Example Contact Form
 *
 * Demonstrates how to create a form using the MK Form package.
 * This is an example implementation that you can use as a reference.
 */
class ContactForm extends Form
{
    public function configure(): void
    {
        $this->setTitle('Contact Us')
            ->setConfiguration([
                'width' => 'max-w-2xl',
                'submitLabel' => 'Send Message',
                'layout' => 'vertical',
            ]);
    }

    public function fields(): array
    {
        return [
            TextInputField::make('name')
                ->setLabel('Full Name')
                ->setRequired(true)
                ->setPlaceholder('Enter your full name')
                ->maxLength(100),

            TextInputField::make('email')
                ->setLabel('Email Address')
                ->setRequired(true)
                ->setPlaceholder('Enter your email address')
                ->rules(['email']),

            SelectField::make('subject')
                ->setLabel('Subject')
                ->setRequired(true)
                ->setPlaceholder('Select a subject')
                ->setOptions([
                    ['label' => 'General Inquiry', 'value' => 'general'],
                    ['label' => 'Technical Support', 'value' => 'support'],
                    ['label' => 'Billing Question', 'value' => 'billing'],
                    ['label' => 'Partnership', 'value' => 'partnership'],
                ]),

            TextareaField::make('message')
                ->setLabel('Message')
                ->setRequired(true)
                ->setPlaceholder('Please enter your message here...')
                ->rows(6)
                ->maxLength(1000),

            CheckboxField::make('newsletter')
                ->setLabel('Subscribe to our newsletter')
                ->setHelpText('Stay updated with our latest news and updates'),

            CheckboxField::make('terms')
                ->setLabel('I agree to the Terms and Conditions')
                ->setRequired(true)
                ->setHelpText('You must agree to continue'),
        ];
    }

    public function handle(mixed $request): array
    {
        // Here you would typically:
        // 1. Validate the data (already done by the service)
        // 2. Save to database
        // 3. Send emails
        // 4. Return success/error response

        // Example: Log the contact request
        \Log::info('Contact form submitted', [
            'name' => $request['name'] ?? '',
            'email' => $request['email'] ?? '',
            'subject' => $request['subject'] ?? '',
            'newsletter' => $request['newsletter'] ?? false,
        ]);

        // Example: Send notification email
        // Mail::to('admin@example.com')->send(new ContactFormSubmitted($request));

        return [
            'success' => true,
            'message' => 'Thank you for your message! We will get back to you within 24 hours.',
            'reference' => 'MSG-' . now()->format('Ymd-His'),
        ];
    }
}
