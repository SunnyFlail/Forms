<?php

namespace SunnyFlail\Forms\Fields;

use SunnyFlail\HtmlAbstraction\Interfaces\IElement;
use SunnyFlail\HtmlAbstraction\Elements\InputElement;
use SunnyFlail\Forms\Constraints\EmailConstraint;

final class EmailField extends InputField
{

    protected string $type = "email";

    public function __construct(
        string $name,
        bool $required = true,
        protected bool $rememberValue = true,
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
        parent::__construct(
            name: $name,
            type: 'email',
            required: $required,
            labelText: $labelText,
            errorMessages: $errorMessages,
            topElements: $topElements,
            middleElements: $middleElements,
            bottomElements: $bottomElements,
            errorAttributes: $errorAttributes,
            labelAttributes: $labelAttributes,
            containerAttributes: $containerAttributes,
            constraints: [new EmailConstraint()]
        );
    }

    public function getInputElement(): IElement
    {
        $attributes = $this->inputAttributes;
        $attributes['minlength'] = 5;
        $attributes['maxlength'] = 254;
        $value = $this->rememberValue ? $this->value : null;

        return new InputElement(
            id: $this->getInputId(),
            type: $this->type,
            name: $this->getFullName(),
            attributes: $attributes,
            value: $value
        );
    }

}