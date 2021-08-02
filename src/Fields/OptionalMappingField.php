<?php

namespace SunnyFlail\Forms\Fields;

use SunnyFlail\Forms\Exceptions\InvalidFieldException;
use SunnyFlail\Forms\Interfaces\IField;
use SunnyFlail\Forms\Interfaces\IFormElement;
use SunnyFlail\Forms\Interfaces\IInputField;
use SunnyFlail\Forms\Interfaces\IMappableContainer;
use SunnyFlail\Forms\Interfaces\IWrapperField;
use SunnyFlail\Forms\Traits\ClassMappedTrait;
use SunnyFlail\Forms\Traits\FieldTrait;
use SunnyFlail\Forms\Traits\WrapperFieldTrait;
use SunnyFlail\HtmlAbstraction\Elements\ContainerElement;
use SunnyFlail\HtmlAbstraction\Interfaces\IElement;

class OptionalMappingField implements IField, IMappableContainer, IWrapperField
{

    use FieldTrait, WrapperFieldTrait, ClassMappedTrait;

    private bool $isChecked = false;

    public function __construct(
        string $fieldName,
        private IInputField $condition,
        ?string $classFQCN = null,
        array $fields = [],
        private array $outerWrapperAttributes = [],
        private array $innerWrapperAttributes = [],
        array $topElements = [],
        array $middleElements = [],
        array $bottomElements = []
    ) {
        $this->valid = null;
        $this->required = false;

        $this->fieldName = $fieldName;
        $this->classFQCN = $classFQCN;
        $this->topElements = $topElements;
        $this->middleElements = $middleElements;
        $this->bottomElements = $bottomElements;

        $elements = [];
        foreach ($fields as $field) {
            $elements[$field->getName()] = $field;
        }

        $this->fields = $elements;
    }

    public function resolve(array $values): bool
    {
        $this->valid = true;
        if (!$this->condition->resolve($values)) {
            $this->required = true;
            return $this->valid;
        }

        foreach ($this->fields as $name => $field) {
            if (!$field->resolve($values)) {
                $this->valid = false;
            }
        }
        return $this->valid;
    }

    public function __toString(): string
    {
        $field = new ContainerElement(
            attributes: $this->innerWrapperAttributes,
            nestedElements: $this->fields
        );

        $elements = [
            ...$this->topElements,
            $this->condition->getContainerElement(),
            ...$this->middleElements,
            $field,
            ...$this->bottomElements
        ];

        return (new ContainerElement(
            attributes: $this->outerWrapperAttributes,
            nestedElements: $elements
        ))->__toString();
    }

    public function withForm(IFormElement $form): IField
    {
        $this->form = $form;
        $this->condition->withForm($form);

        foreach ($this->fields as $field) {
            $field->withForm($form);
        }
        return $this;
    }

    public function getValue(): mixed
    {
        if (!$this->required) {
            if ($this->classFQCN) {
                return null;
            }
            return [];
        }

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
        $elements[] = $this->condition->getInputElement();
        
        foreach ($this->fields as $field) {
            $elements[] = $field->getInputElement();
        }

        return $elements;
    }

    public function getLabelElement(): IElement|array
    {
        $elements = [];
        $elements[] = $this->condition->getLabelElement();

        foreach ($this->fields as $field) {
            $elements[] = $field->getLabelElement();
        }

        return $elements;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function getFields(): array
    {
        $fields = $this->fields;
        $fields[$this->condition->getName()] = $this->condition;

        return $fields;
    }

    public function jsonSerialize()
    {
        return $this->serializeFieldContainer($this);
    }

}