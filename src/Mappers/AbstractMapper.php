<?php

namespace SunnyFlail\Forms\Mappers;

use ReflectionObject;
use SunnyFlail\Forms\Exceptions\MappingException;
use SunnyFlail\Forms\Interfaces\IFieldElement;
use SunnyFlail\Forms\Interfaces\IValueMapper;
use SunnyFlail\Forms\Interfaces\IClassField;
use SunnyFlail\Forms\Interfaces\IMappableField;

abstract class AbstractMapper implements IValueMapper
{

    protected function getFieldValue(IFieldElement $field): mixed
    {
        if ($field instanceof IClassField) {
            $vessel = new $field->getClassName();
            $this->scrapeObject($field, $vessel);
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
    protected function scrapeObject(IMappableField $input, object &$vessel)
    {
        $reflection = new ReflectionObject($vessel);
        $fields = $input->getFields();

        foreach ($fields as $field) {
            $name = $field->getName();

            if (!$reflection->hasProperty($name)) {
                throw new MappingException(sprintf(
                    "Field %s doesn't include field named %s",
                    $input::class, $name
                ));
            }

            $value = $this->getFieldValue($field);
            $property = $reflection->getProperty($name);
            $property->setAccessible(true);
            $property->setValue($vessel, $value);
        }
    }

}