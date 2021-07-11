<?php

namespace SunnyFlail\Forms\Form;

use Psr\Http\Message\ServerRequestInterface;
use SunnyFlail\Forms\Exceptions\FormBuilderException;
use SunnyFlail\Forms\Interfaces\IFieldElement;
use SunnyFlail\Forms\Interfaces\IValueProvider;
use SunnyFlail\Forms\Interfaces\IFormElement;
use SunnyFlail\Forms\Interfaces\IValueMapper;

final class FormBuilder implements IFormBuilder
{

    private IFormElement $form;
    private bool $valid;

    public function __construct(
        private IValueMapper $arrayMapper,
        private IValueMapper $objectMapper,
        private IValueProvider $arrayProvider,
        private IValueProvider $objectProvider,
    ) {
    }

    public function add(IFieldElement $field): IFormBuilder
    {
        $field = $field->withForm($this->form);
        $this->form->withField($field);

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
            throw new FormBuilderException(
                sprintf(
                    "%s isn't a valid form!", $formFQCN
                )
            );
        }

        return new $formFQCN;
    }

    public function getValue(): object|array
    {
        if ($this->valid === false) {
            throw new FormBuilderException(
                sprintf(
                    "Form %s isn't valid!", $this->form->getName()
                )
            );
        }

        $fields = $this->form->getFields();

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