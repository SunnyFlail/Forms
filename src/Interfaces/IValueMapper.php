<?php

namespace SunnyFlail\Forms\Interfaces;

use SunnyFlail\Forms\Exceptions\MappingException;

interface IValueMapper
{

    /**
     * Maps field values to the vessel
     * 
     * MAY throw exception for objects
     * 
     * @param IFormElement $form Form to scrape data from
     * 
     * @return object|array
     * 
     * @throws MappingException if there is no property to corresponding field
     */
    public function scrapeValues(IFormElement $form): object|array;

}