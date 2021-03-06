<?php

namespace SunnyFlail\Forms\Mappers;

use SunnyFlail\Forms\Interfaces\IConditionalField;
use SunnyFlail\Forms\Interfaces\IMappableContainer;
use SunnyFlail\Forms\Interfaces\IFormElement;
use SunnyFlail\Forms\Interfaces\IValueMapper;
use SunnyFlail\Forms\Interfaces\IField;
use SunnyFlail\Forms\Interfaces\ISerializedEntityField;
use SunnyFlail\ObjectCreator\IObjectCreator;

class ValueMapper implements IValueMapper
{
    public function __construct(private IObjectCreator $creator) {}

    public function scrapeValues(IFormElement $form): mixed
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
            if (null !== ($classFQCN = $field->getClassName())) {
                return $this->scrapeObject($field, $classFQCN);
            }
            return $this->scrapeArray($field);
        }

        return $field->getValue();
    }

    protected function scrapeArray(IMappableContainer $input): array
    {
        if (($input instanceof IConditionalField) && !$input->isRequired()) {
            return [];
        }

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
    protected function scrapeObject(IMappableContainer $input, string $classFQCN): ?object
    {
        if (($input instanceof IConditionalField) && !$input->isRequired()) {
            return null;
        }

        $fields = $input->getFields();
        $creator = $this->creator->create($classFQCN);

        foreach ($fields as $field) {
            $name = $field->getName();
            $value = $field->getValue();

            $creator->withProperty($name, $value);
        }

        return $creator->getObject();
    }

    /**
     * @param ISerializedEntityField $field
     */
    protected function scrapeSerializingField(IField $field): mixed
    {
        if (null === ($classFQCN = $field->getClassName())) {
            return $field->getValue();
        }
        return $this->scrapeSerializedEntity($field->getValue(), $classFQCN);
    }

    protected function scrapeSerializedEntity(array $values, string $classFQCN): Object
    {
        $creator = $this->creator->create($classFQCN);

        foreach ($values as $property => $value) {
            $creator->withProperty($property, $value);
        }

        return $creator->getObject();
    }

}