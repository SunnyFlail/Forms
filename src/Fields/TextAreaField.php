<?php

namespace SunnyFlail\Forms\Fields;

use SunnyFlail\Forms\Interfaces\IField;
use SunnyFlail\Forms\Interfaces\IInputField;
use SunnyFlail\HtmlAbstraction\Elements\InputElement;
use SunnyFlail\HtmlAbstraction\Interfaces\IElement;
use SunnyFlail\HtmlAbstraction\Elements\TextAreaElement;

final class TextAreaField extends AbstractInputField
{

    public function __construct(
        string $name,
        protected string $type = "text",
        bool $required = true,
        protected bool $rememberValue = true,
        array $constraints = [],
        array $errorMessages = [],
        array $nestedElements = [],
        protected array $inputAttributes = [],
        array $wrapperAttributes = [],
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
        $this->nestedElements = $nestedElements;
        $this->errorAttributes = $errorAttributes;
        $this->labelAttributes = $labelAttributes;
        $this->wrapperAttributes = $wrapperAttributes;
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

}