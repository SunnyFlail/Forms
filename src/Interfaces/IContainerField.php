<?php

namespace SunnyFlail\Forms\Interfaces;

use SunnyFlail\HtmlAbstraction\Interfaces\IElement;

interface IContainerField extends IElement
{
    /**
     * Adds a new Element on top of the container
     * 
     * @param IElement $element
     * 
     * @return IContainerField
     */
    public function addElementAtStart(IElement $element): IContainerField;

    /**
     * Adds a new Element in the middle of the container
     * 
     * @param IElement $element
     * 
     * @return IContainerField
     */
    public function addElementInMiddle(IElement $element): IContainerField;

    /**
     * Adds a new Element at the bottom of the container
     * 
     * @param IElement $element
     * 
     * @return IContainerField
     */
    public function addElementAtEnd(IElement $element): IContainerField;

}