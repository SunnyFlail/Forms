<?php

namespace SunnyFlail\Forms\Fields;

use SunnyFlail\Forms\Interfaces\IFormElement;
use SunnyFlail\Forms\Interfaces\IInputField;
use SunnyFlail\Forms\Interfaces\IField;
use SunnyFlail\Forms\Traits\FieldTrait;
use SunnyFlail\HtmlAbstraction\Interfaces\IElement;

final class RepeatedInputField implements IField
{

    use FieldTrait;

    public function __construct(
        protected IInputField $field,
        protected IInputField $repeatedField,
        protected string $missmatchError = "Fields must match!"
    ) {
        $this->valid = false;
        $this->error = null;
    }

    public function isRequired(): bool
    {
        return $this->field->isRequired();
    }

    public function getValue(): mixed
    {
        return $this->field->getValue();
    }

    public function getName(): string
    {
        return $this->field->getName();
    }

    public function withValue(mixed $value): IField
    {
        $this->field->withValue($value);
        $this->repeatedField->withValue($value);

        return $this;
    }

    public function withForm(IFormElement $form): IField
    {
        $this->field->withForm($form);
        $this->repeatedField->withForm($form);

        return $this;
    }

    public function resolve(array $values): bool
    {
        $this->field->resolve($values);
        $this->repeatedField->resolve($values);

        if ($this->field->getValue() === $this->repeatedField->getValue()) {
           return $this->valid = true;
        }

        $this->field->withError($this->missmatchError);

        return false;
    }

    public function __toString(): string
    {
        return $this->field . $this->repeatedField;
    }

    public function getInputElement(): IElement|array
    {
        return [
            $this->field->getInputElement(),
            $this->repeatedField->getInputElement()
        ];
    }

    public function getLabelElement(): IElement|array
    {
        return [
            $this->field->getInputElement(),
            $this->repeatedField->getInputElement()
        ];
    }

}