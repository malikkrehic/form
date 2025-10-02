<?php

declare(strict_types=1);

use Mk\Form\Fields\NumberField;

describe('NumberField creation', function () {
    it('can create a number field', function () {
        $field = NumberField::make('quantity');

        expect($field->name)->toBe('quantity');
        expect($field->type)->toBe('number');
        expect($field->label)->toBe('Quantity');
    });

    it('returns field instance for chaining', function () {
        $field = NumberField::make('quantity');
        
        expect($field)->toBeInstanceOf(NumberField::class);
    });
});

describe('NumberField setMin method', function () {
    it('can set minimum value', function () {
        $field = NumberField::make('age')
            ->setMin(18);

        expect($field->props['min'])->toBe(18.0);
        expect($field->rules)->toContain('min:18');
    });

    it('setMin returns field instance for chaining', function () {
        $field = NumberField::make('age');
        $result = $field->setMin(18);

        expect($result)->toBe($field);
    });

    it('can set minimum value with decimals', function () {
        $field = NumberField::make('price')
            ->setMin(0.01);

        expect($field->props['min'])->toBe(0.01);
        expect($field->rules)->toContain('min:0.01');
    });
});

describe('NumberField setMax method', function () {
    it('can set maximum value', function () {
        $field = NumberField::make('age')
            ->setMax(100);

        expect($field->props['max'])->toBe(100.0);
        expect($field->rules)->toContain('max:100');
    });

    it('setMax returns field instance for chaining', function () {
        $field = NumberField::make('age');
        $result = $field->setMax(100);

        expect($result)->toBe($field);
    });

    it('can set maximum value with decimals', function () {
        $field = NumberField::make('discount')
            ->setMax(99.99);

        expect($field->props['max'])->toBe(99.99);
        expect($field->rules)->toContain('max:99.99');
    });
});

describe('NumberField setStep method', function () {
    it('can set step value as string', function () {
        $field = NumberField::make('total_amount')
            ->setStep('0.01');

        expect($field->props['step'])->toBe('0.01');
    });

    it('can set step value as float', function () {
        $field = NumberField::make('quantity')
            ->setStep(1.0);

        expect($field->props['step'])->toBe(1.0);
    });

    it('setStep returns field instance for chaining', function () {
        $field = NumberField::make('amount');
        $result = $field->setStep('0.01');

        expect($result)->toBe($field);
    });
});

describe('NumberField decimal method', function () {
    it('can set field as decimal with default precision', function () {
        $field = NumberField::make('price')
            ->decimal();

        expect($field->rules)->toContain('numeric');
        expect($field->rules)->toContain('decimal:0,2');
        expect($field->props['step'])->toBe('0.01');
    });

    it('can set field as decimal with custom precision', function () {
        $field = NumberField::make('price')
            ->decimal(3);

        expect($field->rules)->toContain('numeric');
        expect($field->rules)->toContain('decimal:0,3');
        expect($field->props['step'])->toBe('0.001');
    });

    it('decimal returns field instance for chaining', function () {
        $field = NumberField::make('price');
        $result = $field->decimal(2);

        expect($result)->toBe($field);
    });
});

describe('NumberField integer method', function () {
    it('can set field as integer', function () {
        $field = NumberField::make('quantity')
            ->integer();

        expect($field->rules)->toContain('integer');
        expect($field->props['step'])->toBe('1');
    });

    it('integer returns field instance for chaining', function () {
        $field = NumberField::make('quantity');
        $result = $field->integer();

        expect($result)->toBe($field);
    });
});

describe('NumberField positive method', function () {
    it('can set field to accept only positive numbers', function () {
        $field = NumberField::make('amount')
            ->positive();

        expect($field->props['min'])->toBe(0.0);
        expect($field->rules)->toContain('min:0');
    });

    it('positive returns field instance for chaining', function () {
        $field = NumberField::make('amount');
        $result = $field->positive();

        expect($result)->toBe($field);
    });
});

describe('NumberField between method', function () {
    it('can set a range for the number field', function () {
        $field = NumberField::make('age')
            ->between(18, 65);

        expect($field->props['min'])->toBe(18.0);
        expect($field->props['max'])->toBe(65.0);
        expect($field->rules)->toContain('min:18');
        expect($field->rules)->toContain('max:65');
    });

    it('between returns field instance for chaining', function () {
        $field = NumberField::make('age');
        $result = $field->between(18, 65);

        expect($result)->toBe($field);
    });

    it('can set a decimal range', function () {
        $field = NumberField::make('discount')
            ->between(0.01, 99.99);

        expect($field->props['min'])->toBe(0.01);
        expect($field->props['max'])->toBe(99.99);
    });
});

describe('NumberField complete configuration', function () {
    it('can configure number field with all methods', function () {
        $field = NumberField::make('total_amount')
            ->setLabel('Total Amount')
            ->setRequired(false)
            ->setPlaceholder('0.00')
            ->setStep('0.01')
            ->setMin(0)
            ->setMax(10000);

        $array = $field->toArray();

        expect($array['name'])->toBe('total_amount');
        expect($array['type'])->toBe('number');
        expect($array['label'])->toBe('Total Amount');
        expect($array['required'])->toBe(false);
        expect($array['placeholder'])->toBe('0.00');
        expect($array['props']['step'])->toBe('0.01');
        expect($array['props']['min'])->toBe(0.0);
        expect($array['props']['max'])->toBe(10000.0);
    });

    it('can configure decimal field with precision', function () {
        $field = NumberField::make('price')
            ->setLabel('Price')
            ->setRequired(true)
            ->decimal(2)
            ->positive();

        expect($field->rules)->toContain('numeric');
        expect($field->rules)->toContain('decimal:0,2');
        expect($field->rules)->toContain('min:0');
        expect($field->props['step'])->toBe('0.01');
        expect($field->props['min'])->toBe(0.0);
    });

    it('can configure integer field with range', function () {
        $field = NumberField::make('quantity')
            ->setLabel('Quantity')
            ->setRequired(true)
            ->integer()
            ->between(1, 100);

        expect($field->rules)->toContain('integer');
        expect($field->props['step'])->toBe('1');
        expect($field->props['min'])->toBe(1.0);
        expect($field->props['max'])->toBe(100.0);
    });

    it('can chain multiple methods fluently', function () {
        $field = NumberField::make('discount_percentage')
            ->setLabel('Discount %')
            ->setPlaceholder('Enter discount percentage')
            ->setHelp('Enter a value between 0 and 100')
            ->decimal(2)
            ->between(0, 100);

        $array = $field->toArray();

        expect($array['label'])->toBe('Discount %');
        expect($array['placeholder'])->toBe('Enter discount percentage');
        expect($array['helpText'])->toBe('Enter a value between 0 and 100');
        expect($array['props']['min'])->toBe(0.0);
        expect($array['props']['max'])->toBe(100.0);
        expect($array['props']['step'])->toBe('0.01');
    });
});
