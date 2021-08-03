<?php

namespace SunnyFlail\Forms\Traits;

use SunnyFlail\HtmlAbstraction\Elements\ContainerElement;
use SunnyFlail\HtmlAbstraction\Interfaces\IElement;

/**
 * Trait for classes having only one Input Element
 */
trait SingleElementFieldTrait
{

    use InputFieldTrait, WrapperFieldTrait, LabeledElementTrait;

    public function __toString(): string
    {
        return $this->getContainerElement()->__toString();
    }

    public function getContainerElement(): IElement|array
    {
        return new ContainerElement(
            attributes: $this->containerAttributes,
            nestedElements: [
                ...$this->topElements,
                $this->getLabelElement(),
                ...$this->middleElements,
                $this->getInputElement(),
                ...$this->bottomElements,
                $this->getErrorElement()
            ]
        );
    }

}