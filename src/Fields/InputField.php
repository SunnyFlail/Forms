<?php

namespace SunnyFlail\Forms\Fields;

use SunnyFlail\HtmlAbstraction\Elements\InputElement;
use SunnyFlail\HtmlAbstraction\Interfaces\IElement;

final class InputField extends AbstractInputField
{

    public function __construct(
        string $name = "text",
        protected string $type = "text",
        bool $required = true,
        array $errorMessages = [],
        protected array $inputAttributes = [],
        array $wrapperAttributes = [],
        array $errorAttributes = [],
        ?string $labelText = null,
        array $labelAttributes = [],
        array $nestedElements = []
    ) {
        parent::__construct(
            name: $name,
            required: $required,
            errorMessages: $errorMessages,
            wrapperAttributes: $wrapperAttributes,
            errorAttributes: $errorAttributes,
            labelText: $labelText,
            labelAttributes: $labelAttributes,
            nestedElements: $nestedElements
        );

        $this->constraints = [];
    }

    protected function getInputElement(): IElement
    {
        return new InputElement(
            id: $this->getInputId(),
            type: $this->type,
            name: $this->getFullName(),
            attributes: $this->inputAttributes
        );
    }

}