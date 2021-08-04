<?php

namespace SunnyFlail\Forms\Fields;

use SunnyFlail\HtmlAbstraction\Elements\InputElement;
use SunnyFlail\HtmlAbstraction\Interfaces\IElement;

class ButtonField extends AbstractInputField
{


    public function __construct(
        string $name,
        bool $required = true,
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
        $this->labelText = $labelText ?? $name;
        $this->topElements = $topElements;
        $this->middleElements = $middleElements;
        $this->bottomElements = $bottomElements;
        $this->errorAttributes = $errorAttributes;
        $this->labelAttributes = $labelAttributes;
        $this->containerAttributes = $containerAttributes;
    }

    public function resolve(array $values): bool
    {
        return $this->valid = true;
    }

    public function getInputElement(): IElement|array
    {
        return new InputElement(
            $this->getInputId(),
            'button',
            $this->getFullName(),
            null,
            false,
            $this->labelText,
            $this->inputAttributes
        );
    }

    public function jsonSerialize()
    {
        $attributes = $this->inputAttributes;
        $attributes['type'] = 'button';

        return [
            'tagName' => "INPUT",
            'name' => $this->getFullName(),
            'id' => $this->getInputId(),
            'valid' => true,
            'label' => $this->labelText,
            'value' => $this->labelText,
            'error' => $this->error,
            'attributes' => $attributes
        ];
    }

}