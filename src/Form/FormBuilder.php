<?php

namespace SunnyFlail\Forms\Form;

use SunnyFlail\Forms\Exceptions\FormBuilderException;
use SunnyFlail\Forms\Interfaces\IProviderFactory;
use SunnyFlail\Forms\Interfaces\IFormElement;
use SunnyFlail\Forms\Interfaces\IValueMapper;
use SunnyFlail\Forms\Interfaces\IField;
use Psr\Http\Message\ServerRequestInterface;

final class FormBuilder implements IFormBuilder
{

    private IFormElement $form;

    public function __construct(
        private IValueMapper $mapper,
        private IProviderFactory $providerFactory,
    ) {}

    public function add(IField $field): IFormBuilder
    {
        $field = $field->withForm($this->form);
        $this->form->withFields($field);

        return $this;
    }

    public function processForm(ServerRequestInterface $request): bool
    {
        return $this->form->resolveForm($request);
    }

    public function buildForm(string $formFQCN, array|object|null $value = null): IFormBuilder
    {
        $copy = clone $this;

        $copy->valid = false;

        $form = $copy->invokeForm($formFQCN);
        $copy->fillFieldValues($form, $value);
        
        return $copy;
    }

    private function invokeForm(string $formFQCN): IFormElement
    {
        if (!class_exists($formFQCN) || !($formFQCN instanceof IFormElement)) {
            throw new FormBuilderException(sprintf(
                "%s isn't a valid form!", $formFQCN
            ));
        }

        return new $formFQCN;
    }

    public function getProcessedData(): object|array
    {
        return $this->mapper->scrapeValues($this->form);
    }

    private function fillFieldValues(IFormElement $form, array|object|null $value)
    {
        return $this->providerFactory->getProvider($value)
            ->fill($form, $value);
    }

    public function __toString()
    {
        return $this->form;
    }

}