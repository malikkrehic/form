<?php

declare(strict_types=1);

use Mk\Form\Fields\NumberField;

it('matches the exact user example', function () {
    $field = NumberField::make('total_amount')
        ->setLabel('Total Amount')
        ->setRequired(false)
        ->setPlaceholder('0.00')
        ->setStep('0.01');

    $array = $field->toArray();

    // Verify all properties
    expect($array['name'])->toBe('total_amount');
    expect($array['label'])->toBe('Total Amount');
    expect($array['required'])->toBe(false);
    expect($array['placeholder'])->toBe('0.00');
    expect($array['props']['step'])->toBe('0.01');
    
    // Dump the full array for inspection
    dump($array);
});
