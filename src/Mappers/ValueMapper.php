<?php

namespace SunnyFlail\Forms\Mappers;

use SunnyFlail\Forms\Exceptions\MappingException;
use SunnyFlail\Forms\Interfaces\IMappableField;
use SunnyFlail\Forms\Interfaces\IFormElement;
use SunnyFlail\Forms\Interfaces\IValueMapper;
use SunnyFlail\Forms\Interfaces\IField;
use ReflectionClass;

class ValueMapper implements IValueMapper
{

    public function scrapeValues(IFormElement $form): object|array
    {
        return $this->getFieldValue($form);
    }

    /**
     * Returns the value of a field
     * 
     * @param IField|IFormElement $field
     * 
     * @return mixed
     */
    protected function getFieldValue(IField|IFormElement $field): mixed
    {
        if ($field instanceof IMappableField) {
            if (null !== ($className = $field->getClassName())) {
                return $this->scrapeObject($field, $className);
            }
            return $this->scrapeArray($field);
        }

        return $field->getValue();
    }

    protected function scrapeArray(IMappableField $input): array
    {
        $vessel = [];
        foreach ($input->getFields() as $name => $field) {
            $vessel[$name] = $this->getFieldValue($field);
        }

        return $vessel;
    }

    /**
     * Scrapes values from fields into an entity
     * 
     * @param IMappableField $input Input to scrape data from
     * @param object $vessel Reference to object that will be filled with values
     *
     * @return void 
     */
    protected function scrapeObject(IMappableField $input, string $classFQCN): object
    {
        $classFQCN = "\\" . $classFQCN;
        if (class_exists($classFQCN)) {
            throw new MappingException(sprintf(
                "Class provided to field %s doesn't exist!", $classFQCN
            ));
        }

        $reflection = new ReflectionClass($classFQCN);
        $vessel = $reflection->newInstanceWithoutConstructor();
        $fields = $input->getFields();

        foreach ($fields as $field) {
            $name = $field->getName();

            if (false === $reflection->hasProperty($name)) {
                throw new MappingException(sprintf(
                    "Class %s doesn't have property named %s",
                    $reflection->getShortName(), $name
                ));
            }

            $value = $this->getFieldValue($field);
            $property = $reflection->getProperty($name);
            $property->setAccessible(true);
            $property->setValue($vessel, $value);
        }

        return $vessel;
    }

}