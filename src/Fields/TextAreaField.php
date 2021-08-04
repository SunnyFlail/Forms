<?php

namespace SunnyFlail\Forms\Fields;

use SunnyFlail\Forms\Traits\ResolveInputTrait;
use SunnyFlail\HtmlAbstraction\Interfaces\IElement;
use SunnyFlail\HtmlAbstraction\Elements\TextAreaElement;

final class TextAreaField extends AbstractInputField
{

    use ResolveInputTrait;

    public function __construct(
        string $name,
        bool $required = true,
        protected bool $rememberValue = true,
        array $constraints = [],
        array $errorMessages = [],
        array $topElements = [],
        array $middleElements = [],
        array $bottomElements = [],
        protected array $inputAttributes = [],
        array $containerAttributes = [],
        array $errorAttributes = [],
        ?string $labelText = null,
        array $labelAttributes = []
    ) {
        parent::__construct();

        $this->name = $name;
        $this->required = $required;
        $this->labelText = $labelText;
        $this->constraints = $constraints;
        $this->errorMessages = $errorMessages;
        $this->topElements = $topElements;
        $this->middleElements = $middleElements;
        $this->bottomElements = $bottomElements;
        $this->errorAttributes = $errorAttributes;
        $this->labelAttributes = $labelAttributes;
        $this->containerAttributes = $containerAttributes;
    }

    public function getInputElement(): IElement
    {
        $value = $this->rememberValue ? $this->value : null;

        return new TextAreaElement(
            id: $this->getInputId(),
            name: $this->getFullName(),
            attributes: $this->inputAttributes,
            value: $value
        );
    }

    public function jsonSerialize()
    {
        return [
            'tagName' => "TEXTAREA",
            'name' => $this->getFullName(),
            'id' => $this->getInputId(),
            'required' => $this->required,
            'valid' => $this->valid,
            'label' => $this->labelText ?? $this->name,
            'value' => $this->value,
            'error' => $this->error,
            'attributes' => $this->inputAttributes
        ];
    }

}