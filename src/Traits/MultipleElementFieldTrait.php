<?php

namespace SunnyFlail\Forms\Traits;

use SunnyFlail\HtmlAbstraction\Interfaces\IElement;

/**
 * Trait for Fields that render into multiple Input Elements
 */
trait MultipleElementFieldTrait
{

    public function __toString(): string
    {
        return implode('', $this->getContainerElement());
    }

    abstract public function getContainerElement(): IElement|array;

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