<?php

namespace SunnyFlail\Forms\Interfaces;

use SunnyFlail\HtmlAbstraction\Interfaces\IElement;

/**
 * Interface for elements that contain fields
 */
interface IFieldContainer extends IElement
{

    /**
     * Returns fields
     * 
     * @return IField[]
     */
    public function getFields(): array;

}