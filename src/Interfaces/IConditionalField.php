<?php

namespace SunnyFlail\Forms\Interfaces;

/**
 * Interface for fields whose value should only be validated if a condition is met
 */
interface IConditionalField extends IField
{

    /**
     * Should this field's values be scraped
     * 
     * @return bool
     */
    public function isScrapable(): bool;

}