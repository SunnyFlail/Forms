<?php

namespace SunnyFlail\Forms\Mappers;

use SunnyFlail\Forms\Interfaces\IFormElement;

final class ObjectMapper extends AbstractMapper
{

    function scrapeForm(IFormElement $form, mixed &$vessel)
    {
        $this->fillObject($form, $vessel);
        return $vessel;
    }
    
}