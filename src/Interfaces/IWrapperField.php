<?php

namespace SunnyFlail\Forms\Interfaces;

use SunnyFlail\HtmlAbstraction\Interfaces\IElement;

interface IWrapperField extends IElement
{
    /**
     * Adds a new Element on top of the container
     * 
     * @param IElement $element
     * 
     * @return IWrapperField
     */
    public function addElementAtStart(IElement $element): IWrapperField;

    /**
     * Adds a new Element in the middle of the container
     * 
     * @param IElement $element
     * 
     * @return IWrapperField
     */
    public function addElementInMiddle(IElement $element): IWrapperField;

    /**
     * Adds a new Element at the bottom of the container
     * 
     * @param IElement $element
     * 
     * @return IWrapperField
     */
    public function addElementAtEnd(IElement $element): IWrapperField;

}