<?php

namespace SunnyFlail\Forms\Fields;

use SunnyFlail\Forms\Traits\ResolveInputTrait;
use SunnyFlail\HtmlAbstraction\Elements\InputElement;
use SunnyFlail\HtmlAbstraction\Interfaces\IElement;

class InputField extends AbstractInputField
{

    use ResolveInputTrait;

    public function __construct(
        string $name,
        protected string $type = "text",
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
        $this->topElements = $topElements;
        $this->middleElements = $middleElements;
        $this->bottomElements = $bottomElements;
        $this->errorMessages = $errorMessages;
        $this->errorAttributes = $errorAttributes;
        $this->labelAttributes = $labelAttributes;
        $this->containerAttributes = $containerAttributes;
    }

    public function getInputElement(): IElement
    {
        $value = $this->rememberValue ? $this->value : null;

        return new InputElement(
            id: $this->getInputId(),
            type: $this->type,
            name: $this->getFullName(),
            attributes: $this->inputAttributes,
            value: $value
        );
    }

    public function jsonSerialize()
    {
        $attributes = $this->inputAttributes;
        $attributes['type'] = $this->type;

        return [
            'tagName' => 'INPUT',
            'name' => $this->getFullName(),
            'id' => $this->getInputId(),
            'required' => $this->required,
            'valid' => $this->valid,
            'label' => $this->labelText ?? $this->name,
            'value' => $this->value,
            'error' => $this->error,
            'attributes' => $attributes
        ];
    }

}