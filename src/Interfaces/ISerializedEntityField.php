<?php

namespace SunnyFlail\Forms\Interfaces;

/**
 * Interface for fields that hold serialized entities
 */
interface ISerializedEntityField
{

    /**
     * Returns the name of class to which this field's value deserializes into
     * 
     * @return string|null
     */
    public function getClassName(): ?string;

}