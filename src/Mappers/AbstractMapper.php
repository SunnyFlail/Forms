<?php

namespace SunnyFlail\Forms\Mappers;

use ReflectionObject;
use SunnyFlail\Forms\Exceptions\MappingException;
use SunnyFlail\Forms\Interfaces\IField;
use SunnyFlail\Forms\Interfaces\IValueMapper;
use SunnyFlail\Forms\Interfaces\IClassField;
use SunnyFlail\Forms\Interfaces\IMappableField;

abstract class AbstractMapper implements IValueMapper
{

    /**
     * Returns the value of a field
     * 
     * @param IField $field
     * 
     * @return mixed
     */
    protected function getFieldValue(IField $field): mixed
    {
        if ($field instanceof IClassField) {
            $vessel = new $field->getClassName();
            $this->fillObject($field, $vessel);
            return $vessel;
        }

        return $field->getValue();
    }

    /**
     * Scrapes values from fields into an entity
     * 
     * @param IMappableField $input Input to scrape data from
     * @param object $vessel Reference to object that will be filled with values
     *
     * @return void 
     */
    protected function fillObject(IMappableField $input, object &$vessel)
    {
        $reflection = new ReflectionObject($vessel);
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
    }

}