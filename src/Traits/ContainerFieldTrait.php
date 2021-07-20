<?php

namespace SunnyFlail\Forms\Traits;

use SunnyFlail\Forms\Interfaces\IContainerField;
use SunnyFlail\HtmlAbstraction\Interfaces\IElement;

/**
 * Trait for classes implementing IContainerField interface
 */
trait ContainerFieldTrait
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
     * @return IContainerField
     */
    public function addElementAtStart(IElement $element): IContainerField
    {
        $this->topElements[] = $element;
        return $this;
    }

    /**
     * Adds a new Element in the middle of the container
     * 
     * @param IElement $element
     * 
     * @return IContainerField
     */
    public function addElementInMiddle(IElement $element): IContainerField
    {
        $this->middleElements[] = $element;
        return $this;
    }

    /**
     * Adds a new Element at the bottom of the container
     * 
     * @param IElement $element
     * 
     * @return IContainerField
     */
    public function addElementAtEnd(IElement $element): IContainerField
    {
        $this->bottomElements[] = $element;
        return $this;
    }

}