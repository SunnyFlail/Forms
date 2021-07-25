<?php

namespace SunnyFlail\Forms\Fields;

use SunnyFlail\Forms\Interfaces\IWrapperField;
use SunnyFlail\HtmlAbstraction\Elements\ContainerElement;
use SunnyFlail\HtmlAbstraction\Elements\FileElement;
use SunnyFlail\Forms\Interfaces\IFileConstraint;
use SunnyFlail\Forms\Interfaces\IInputField;
use SunnyFlail\Forms\Interfaces\IFileField;
use SunnyFlail\Forms\Traits\WrapperFieldTrait;
use SunnyFlail\Forms\Traits\FileUploadFieldTrait;
use SunnyFlail\Forms\Traits\LabeledElementTrait;
use SunnyFlail\HtmlAbstraction\Interfaces\IElement;

final class FileUploadField implements IInputField, IFileField, IWrapperField
{
    
    use FileUploadFieldTrait, LabeledElementTrait, WrapperFieldTrait;

    /**
     * @param IFileConstraint[] $constraints
     */
    public function __construct(
        string $name,
        bool $required = true,
        bool $multiple = true,
        array $constraints = [],
        array $topElements = [],
        array $middleElements = [],
        array $bottomElements = [],
        array $errorMessages = [],
        protected array $wrapperAttributes = [],
        array $labelAttributes = [],
        ?string $labelText = null,
        protected array $inputAttributes = [],
        protected bool $terminateOnError = false
    ) {
        $this->valid = false;
        $this->error = null;
        $this->value = null;
        $this->name = $name;
        $this->required = $required;
        $this->multiple = $multiple;
        $this->labelText = $labelText;
        $this->constraints = $constraints;
        $this->topElements = $topElements;
        $this->middleElements = $middleElements;
        $this->bottomElements = $bottomElements;
        $this->labelAttributes = $labelAttributes;
        $this->errorMessages = $errorMessages;
    }

    public function __toString(): string
    {
        return $this->getContainerElement()->__toString();
    }

    public function getContainerElement(): IElement|array
    {
        return new ContainerElement(
            attributes: $this->wrapperAttributes,
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

    public function getInputElement(): IElement|array
    {
        return new FileElement(
            name: $this->getFullName(),
            id: $this->getInputId(),
            multiple: $this->multiple,
            attributes: $this->inputAttributes
        );
    }

}