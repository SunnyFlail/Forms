<?php

namespace SunnyFlail\Forms\Interfaces;

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
     */
    public function scrapeValues(IFormElement $form): object|array;

}