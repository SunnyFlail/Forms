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
     * @param mixed $vessel Reference to object or array that will be filled with data
     * 
     * @return void
     * @throws MappingException if there is no property to corresponding field
     */
    public function scrapeForm(IFormElement $form, mixed &$vessel);

}