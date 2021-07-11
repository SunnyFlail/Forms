<?php

namespace SunnyFlail\Forms\Mappers;

use SunnyFlail\Forms\Interfaces\IFormElement;

final class ObjectMapper extends AbstractMapper
{

    function get(IFormElement $form, mixed &$vessel)
    {
        $this->scrapeObject($form, $vessel);
        return $vessel;
    }
    
}