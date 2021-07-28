<?php

namespace SunnyFlail\Forms\Mappers;

use SunnyFlail\Forms\Interfaces\IMappableContainer;
use SunnyFlail\Forms\Interfaces\IFormElement;
use SunnyFlail\Forms\Interfaces\IValueMapper;
use SunnyFlail\Forms\Interfaces\IField;
use SunnyFlail\ObjectCreator\IObjectCreator;

class ValueMapper implements IValueMapper
{
    public function __construct(private IObjectCreator $creator) {}

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
        if ($field instanceof IMappableContainer) {
            if (null !== ($className = $field->getClassName())) {
                return $this->scrapeObject($field, $className);
            }
            return $this->scrapeArray($field);
        }

        return $field->getValue();
    }

    protected function scrapeArray(IMappableContainer $input): array
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
     * @param IMappableContainer $input  Input to scrape data from
     * @param object         $vessel Reference to object that will be filled with values
     *
     * @return void 
     */
    protected function scrapeObject(IMappableContainer $input, string $classFQCN): object
    {
        $creator = $this->creator->create($classFQCN);
        $fields = $input->getFields();

        foreach ($fields as $field) {
            $name = $field->getName();
            $value = $field->getValue();

            $creator->withProperty($name, $value);
        }

        return $creator->getObject();
    }

}