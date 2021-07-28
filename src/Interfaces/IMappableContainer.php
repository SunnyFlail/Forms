<?php

namespace SunnyFlail\Forms\Interfaces;

/**
 * Interface for FieldContainers whose values are mapped into an object/array
 */
interface IMappableContainer extends IFieldContainer
{

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
     * @return IMappableContainer $this
     */
    public function withFields(IField ...$fields): IMappableContainer;

    /**
     * Adds value to corresponding field
     * 
     * @param string $fieldName Name of the field
     * @param mixed  $value     Value to add to the field
     * 
     * @return IMappableContainer $this
     */
    public function withFieldValue(string $fieldName, mixed $value): IMappableContainer;

}