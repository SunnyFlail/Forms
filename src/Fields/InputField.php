<?php

namespace SunnyFlail\Forms\Fields;

use SunnyFlail\HtmlAbstraction\Elements\InputElement;
use SunnyFlail\HtmlAbstraction\Interfaces\IElement;

final class InputField extends AbstractInputField
{

    public function __construct(
        string $name,
        protected string $type = "text",
        bool $required = true,
        array $errorMessages = [],
        array $constraints = [],
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
        $this->errorMessages = $errorMessages;
        $this->wrapperAttributes = $wrapperAttributes;
        $this->errorAttributes = $errorAttributes;
        $this->labelText = $labelText;
        $this->labelAttributes = $labelAttributes;
        $this->nestedElements = $nestedElements;
        $this->constraints = $constraints;
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