<?php

namespace SunnyFlail\Forms\Fields;

use SunnyFlail\Forms\Exceptions\InvalidFieldException;
use SunnyFlail\Forms\Interfaces\IMappableField;
use SunnyFlail\Forms\Interfaces\IFormElement;
use SunnyFlail\Forms\Interfaces\IField;
use SunnyFlail\Forms\Traits\MappableTrait;
use SunnyFlail\Forms\Traits\FieldTrait;
use ReflectionObject;
use InvalidArgumentException;

final class ClassMappedField implements IMappableField, IField
{

    use FieldTrait, MappableTrait;
    
    public function __construct(
        protected string $fieldName,
        string $className,
        IField ...$fields
    ) {
        $this->className = $className;
        $this->fields = $fields;
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
                $this->valid = false;
            }
        }
        return $this->valid;
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

    protected function scrapeArrayProperties(array $arr): ClassMappedField
    {
        foreach ($this->fields as $name => $field) {
            if (!isset($arr[$name])) {
                if ($field->isRequired()) {
                    throw new InvalidArgumentException(sprintf(
                        "Value not provided for field",$name
                    ));
                }
                continue;
            }

            $value = $arr[$name];
            $this->fields[$name]->withValue($value);
        }

        return $this;
    }

    protected function scrapeValuesProperties(object $obj): ClassMappedField
    {
        $reflection = new ReflectionObject($obj);
        foreach ($this->fields as $name => $field) {
            if (!$reflection->hasProperty($name)) {
                if ($field->isRequired()) {
                    throw new InvalidArgumentException(sprintf(
                        "Class doesn't have property %s",
                        $reflection->getShortName(), $name
                    ));
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

    public function getValue()
    {
        if (!$this->valid) {
            throw new InvalidFieldException(sprintf(
                "Field %s in form %s is not valid!",
                $this->fieldName,
                $this->form->getName()
            ));
        }

        return array_map(
            fn($field) => $field->getValue(),
            $this->fields
        );
    }

    public function __toString()
    {
        return implode('', $this->fields);
    }

}