<?php

namespace SunnyFlail\Forms\Traits;

use ReflectionObject;
use SunnyFlail\Forms\Exceptions\FormFillingException;
use SunnyFlail\Forms\Interfaces\IField;
use SunnyFlail\Forms\Interfaces\IFormElement;
use SunnyFlail\Forms\Interfaces\IMappableContainer;

/**
 * Trait for IMappableContainers that return an object
 */
trait ClassMappedTrait
{
    protected string $fieldName;

    use FieldTrait, MappableTrait;

    public function getName(): string
    {
        return $this->fieldName;
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

    public function jsonSerialize()
    {
        return $this->serializeFieldContainer($this);
    }

}