<?php

namespace SunnyFlail\Forms\Traits;

trait MultipleElementFieldTrait
{

    /**
     * Returns the id for repeated field element
     * 
     * @param string $baseId Base id for the field group
     * @param string $identifier Private identifier for the element
     * 
     * @return string
     */
    protected function resolveId(string $baseId, string $identifier): string
    {
        $identifier = strtr($identifier, " ", "_");
        return $baseId . '--' . $identifier;
    }

}