<?php

namespace SunnyFlail\Forms\Interfaces;

use SunnyFlail\HtmlAbstraction\Interfaces\IElement;

interface IMappableField extends IElement
{
    
    /**
     * Returns fields
     * 
     * @return IFieldElement[]
     */
    public function getFields(): array;

    /**
     * Checks whether this form has field with provided name
     * 
     * @return bool
     */
    public function hasField(string $fieldName): bool;

    /**
     * Adds fields for mapping
     * 
     * @param IFieldElement[] $fields Fields to add
     * 
     * @return IMappableField $this
     */
    public function withFields(IFieldElement ...$fields): IMappableField;

    /**
     * Maps value to corresponding field
     * 
     * @param string $fieldName Name of the field
     * @param mixed $value Value to map to the field
     * 
     * @return IMappableField $this
     */
    public function withFieldValue(string $fieldName, mixed $value): IMappableField;

}