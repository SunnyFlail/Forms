<?php

namespace SunnyFlail\Forms\Interfaces;

use SunnyFlail\HtmlAbstraction\Interfaces\IElement;

interface IMappableField extends IElement
{
    
    /**
     * Returns fields
     * 
     * @return IField[]
     */
    public function getFields(): array;
    
    /**
     * Returns the name of class 
     * 
     * @return string
     */
    public function getClassName(): ?string;

    /**
     * Checks whether this form has field with provided name
     * 
     * @return bool
     */
    public function hasField(string $fieldName): bool;

    /**
     * Adds fields for mapping
     * 
     * @param IField[] $fields Fields to add
     * 
     * @return IMappableField $this
     */
    public function withFields(IField ...$fields): IMappableField;

    /**
     * Maps value to corresponding field
     * 
     * @param string $fieldName Name of the field
     * @param mixed  $value     Value to map to the field
     * 
     * @return IMappableField $this
     */
    public function withFieldValue(string $fieldName, mixed $value): IMappableField;

}