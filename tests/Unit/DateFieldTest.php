<?php

declare(strict_types=1);

use Mk\Form\Fields\DateField;

describe('DateField creation', function () {
    it('can create a date field', function () {
        $field = DateField::make('birthdate');

        expect($field->name)->toBe('birthdate');
        expect($field->type)->toBe('date');
        expect($field->label)->toBe('Birthdate');
    });

    it('returns field instance for chaining', function () {
        $field = DateField::make('birthdate');
        
        expect($field)->toBeInstanceOf(DateField::class);
    });
});

describe('DateField min method', function () {
    it('can set minimum date', function () {
        $field = DateField::make('start_date')
            ->min('2024-01-01');

        expect($field->props['min'])->toBe('2024-01-01');
        expect($field->rules)->toContain('after_or_equal:2024-01-01');
    });

    it('min returns field instance for chaining', function () {
        $field = DateField::make('start_date');
        $result = $field->min('2024-01-01');

        expect($result)->toBe($field);
    });
});

describe('DateField max method', function () {
    it('can set maximum date', function () {
        $field = DateField::make('end_date')
            ->max('2024-12-31');

        expect($field->props['max'])->toBe('2024-12-31');
        expect($field->rules)->toContain('before_or_equal:2024-12-31');
    });

    it('max returns field instance for chaining', function () {
        $field = DateField::make('end_date');
        $result = $field->max('2024-12-31');

        expect($result)->toBe($field);
    });
});

describe('DateField format method', function () {
    it('can set date format validation', function () {
        $field = DateField::make('event_date')
            ->format('Y-m-d');

        expect($field->rules)->toContain('date_format:Y-m-d');
    });

    it('format returns field instance for chaining', function () {
        $field = DateField::make('event_date');
        $result = $field->format('Y-m-d');

        expect($result)->toBe($field);
    });
});

describe('DateField after method', function () {
    it('can set after date validation', function () {
        $field = DateField::make('deadline')
            ->after('2024-01-01');

        expect($field->rules)->toContain('after:2024-01-01');
    });

    it('after returns field instance for chaining', function () {
        $field = DateField::make('deadline');
        $result = $field->after('2024-01-01');

        expect($result)->toBe($field);
    });
});

describe('DateField before method', function () {
    it('can set before date validation', function () {
        $field = DateField::make('expiry_date')
            ->before('2025-12-31');

        expect($field->rules)->toContain('before:2025-12-31');
    });

    it('before returns field instance for chaining', function () {
        $field = DateField::make('expiry_date');
        $result = $field->before('2025-12-31');

        expect($result)->toBe($field);
    });
});

describe('DateField complete configuration', function () {
    it('can configure date field with all methods', function () {
        $field = DateField::make('appointment_date')
            ->setLabel('Appointment Date')
            ->setRequired(true)
            ->setPlaceholder('Select a date')
            ->setHelp('Choose a date for your appointment')
            ->min('2024-01-01')
            ->max('2024-12-31');

        $array = $field->toArray();

        expect($array['name'])->toBe('appointment_date');
        expect($array['type'])->toBe('date');
        expect($array['label'])->toBe('Appointment Date');
        expect($array['required'])->toBe(true);
        expect($array['placeholder'])->toBe('Select a date');
        expect($array['helpText'])->toBe('Choose a date for your appointment');
        expect($array['props']['min'])->toBe('2024-01-01');
        expect($array['props']['max'])->toBe('2024-12-31');
        expect($array['rules'])->toContain('after_or_equal:2024-01-01');
        expect($array['rules'])->toContain('before_or_equal:2024-12-31');
    });

    it('can chain multiple validation rules', function () {
        $field = DateField::make('event_date')
            ->after('2024-01-01')
            ->before('2024-12-31')
            ->format('Y-m-d');

        expect($field->rules)->toContain('after:2024-01-01');
        expect($field->rules)->toContain('before:2024-12-31');
        expect($field->rules)->toContain('date_format:Y-m-d');
    });

    it('can set min and max date range', function () {
        $field = DateField::make('booking_date')
            ->min('2024-06-01')
            ->max('2024-06-30');

        expect($field->props['min'])->toBe('2024-06-01');
        expect($field->props['max'])->toBe('2024-06-30');
    });
});
