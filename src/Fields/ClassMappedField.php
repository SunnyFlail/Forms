<?php

namespace SunnyFlail\Forms\Fields;

use SunnyFlail\Forms\Exceptions\InvalidFieldException;
use SunnyFlail\Forms\Interfaces\IMappableContainer;
use SunnyFlail\Forms\Interfaces\IFormElement;
use SunnyFlail\Forms\Interfaces\IField;
use SunnyFlail\Forms\Traits\MappableTrait;
use SunnyFlail\Forms\Traits\FieldTrait;
use InvalidArgumentException;
use ReflectionObject;
use SunnyFlail\Forms\Exceptions\FormFillingException;
use SunnyFlail\HtmlAbstraction\Interfaces\IElement;

final class ClassMappedField implements IMappableContainer, IField
{

    use FieldTrait, MappableTrait;
    
    public function __construct(
        protected string $fieldName,
        string $className,
        IField ...$fields
    ) {
        $this->className = $className;

        $elements = [];
        foreach ($fields as $field) {
            $elements[$field->getName()] = $field;
        }

        $this->fields = $elements;
    }

    public function getName(): string
    {
        return $this->fieldName;
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function resolve(array $values): bool
    {
        $this->valid = true;
        foreach ($this->fields as $name => $field) {
            if (!$field->resolve($values) && $field->isRequired()) {
                $this->valid = null;
            }
        }
        return $this->valid;
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

    public function withForm(IFormElement $form): IField
    {
        foreach ($this->fields as $field) {
            $field->withForm($form);
        }
        return $this;
    }

    public function withValue(mixed $value): IField
    {
        if (is_object($value)) {
            return $this->scrapeValuesProperties($value);
        }
        if (is_array($value)) {
            return $this->scrapeArrayProperties($value);
        }

        return $this;
    }

    /**
     * Scrapes values from an array
     * 
     * @param array $arr An associative array
     * 
     * @return IMappableContainer
     * @throws FormFillingException If the array doesn't have value with a required field's name as key
     */
    protected function scrapeArrayProperties(array $arr): IMappableContainer
    {
        foreach ($this->fields as $name => $field) {
            if (!isset($arr[$name])) {
                if ($field->isRequired()) {
                    throw new FormFillingException(
                        sprintf(
                            "Value not provided for field %s!", $name
                        )
                    );
                }
                continue;
            }

            $value = $arr[$name];
            $this->fields[$name]->withValue($value);
        }

        return $this;
    }

    /**
     * Scrapes values from an object
     * 
     * @param object $obj
     * 
     * @return IMappableContainer
     * @throws FormFillingException If the object doesn't have property with a required field's name
     */
    protected function scrapeValuesProperties(object $obj): IMappableContainer
    {
        $reflection = new ReflectionObject($obj);

        foreach ($this->fields as $name => $field) {

            if (!$reflection->hasProperty($name)) {

                if ($field->isRequired()) {
                    throw new FormFillingException(
                        sprintf(
                            "Class %s doesn't have property named %s!",
                            $reflection->getShortName(), $name
                        )
                    );
                }
                continue;
            }

            $property = $reflection->getProperty($name);
            $property->setAccessible(true);
            $value = $property->getValue($obj);
            
            $this->fields[$name]->withValue($value);
        }

        return $this;
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