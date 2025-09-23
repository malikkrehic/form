<?php

use Mk\Form\Examples\ContactForm;
use Mk\Form\Services\FormService;

it('has default name based on class name', function () {
    $form = new ContactForm();
    
    expect($form->getName())->toBe('contact');
    expect($form->getEndpoint())->toBe('/forms/contact');
});

it('can have custom name', function () {
    $form = new class extends \Mk\Form\Form {
        public function configure(): void
        {
            $this->setName('custom-contact');
        }

        public function fields(): array
        {
            return [];
        }
    };

    expect($form->getName())->toBe('custom-contact');
    expect($form->getEndpoint())->toBe('/forms/custom-contact');
});

it('includes name in array representation', function () {
    $form = new ContactForm();
    $array = $form->toArray();

    expect($array)->toHaveKey('name');
    expect($array['name'])->toBe('contact');
});

it('uses name as key in form service', function () {
    $form = new ContactForm();
    $formService = new FormService();
    
    $formService->registerForm($form);
    
    expect($formService->hasForm('contact'))->toBeTrue();
    expect($formService->getForm('contact'))->toBe($form);
});
