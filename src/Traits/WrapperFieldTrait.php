<?php

namespace SunnyFlail\Forms\Traits;

use SunnyFlail\Forms\Interfaces\IWrapperField;
use SunnyFlail\HtmlAbstraction\Interfaces\IElement;

/**
 * Trait for classes implementing IWrapperField interface
 */
trait WrapperFieldTrait
{

    /**
     * @var IElement[] Elements at the top of container
     */
    protected array $topElements = [];
    
    /**
     * @var IElement[] Elements in the middle of container
     */
    protected array $middleElements = [];

    /**
     * @var IElement[] Elements at the bottom of container
     */
    protected array $bottomElements = [];

    /**
     * Adds a new Element on top of the container
     * 
     * @param IElement $element
     * 
     * @return IWrapperField
     */
    public function addElementAtStart(IElement $element): IWrapperField
    {
        $this->topElements[] = $element;
        return $this;
    }

    /**
     * Adds a new Element in the middle of the container
     * 
     * @param IElement $element
     * 
     * @return IWrapperField
     */
    public function addElementInMiddle(IElement $element): IWrapperField
    {
        $this->middleElements[] = $element;
        return $this;
    }

    /**
     * Adds a new Element at the bottom of the container
     * 
     * @param IElement $element
     * 
     * @return IWrapperField
     */
    public function addElementAtEnd(IElement $element): IWrapperField
    {
        $this->bottomElements[] = $element;
        return $this;
    }

}