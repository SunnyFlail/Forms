<?php

namespace SunnyFlail\Forms\Fields;

use SunnyFlail\Forms\Exceptions\InvalidFieldException;
use SunnyFlail\Forms\Interfaces\IMappableContainer;
use SunnyFlail\Forms\Interfaces\IField;
use SunnyFlail\Forms\Interfaces\IFormElement;
use SunnyFlail\Forms\Traits\ClassMappedTrait;
use SunnyFlail\HtmlAbstraction\Interfaces\IElement;

final class ClassMappedField implements IMappableContainer, IField
{

    use ClassMappedTrait;
    
    public function __construct(
        string $fieldName,
        string $classFQCN,
        IField ...$fields
    ) {
        $this->valid = null;
        $this->fieldName = $fieldName;
        $this->classFQCN = $classFQCN;

        $elements = [];
        foreach ($fields as $field) {
            $elements[$field->getName()] = $field;
        }

        $this->fields = $elements;
    }

    public function resolve(array $values): bool
    {
        $this->valid = true;
        foreach ($this->fields as $field) {
            if (!$field->resolve($values) && $field->isRequired()) {
                $this->valid = false;
            }
        }
        return $this->valid;
    }

    public function withForm(IFormElement $form): IField
    {
        $this->form = $form;
        foreach ($this->fields as $field) {
            $field->withForm($form);
        }
        return $this;
    }

    public function getValue(): mixed
    {
        if (!$this->valid) {
            throw new InvalidFieldException(
                sprintf(
                    "Field %s in form %s is not valid!",
                    $this->fieldName,
                    $this->form->getName()
                )
            );
        }

        $values = [];

        foreach ($this->fields as $name => $field) {
            $values[$name] = $field->getValue();
        }

        return $values;
    }

    public function getInputElement(): IElement|array
    {
        $elements = [];
        foreach ($this->fields as $field) {
            $elements[] = $field->getInputElement();
        }

        return $elements;
    }

    public function getLabelElement(): IElement|array
    {
        $elements = [];
        foreach ($this->fields as $field) {
            $elements[] = $field->getLabelElement();
        }

        return $elements;
    }

    public function __toString()
    {
        return implode('', $this->fields);
    }

    public function jsonSerialize()
    {
        return $this->serializeFieldContainer($this);
    }

}