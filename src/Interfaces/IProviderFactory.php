<?php

namespace SunnyFlail\Forms\Interfaces;

/**
 * Interface for IValueProvider factory
 */
interface IProviderFactory
{

    /**
     * Returns an appropriate ValueProvider for form
     * 
     * @param mixed $value
     * 
     * @return IValueProvider
     */
    public function getProvider($value): IValueProvider;

}