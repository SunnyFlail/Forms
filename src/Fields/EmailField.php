<?php

namespace SunnyFlail\Forms\Fields;

use SunnyFlail\Forms\Constraints\EmailConstraint;
use SunnyFlail\Forms\Elements\EmailElement;
use SunnyFlail\Forms\Elements\InputElement;
use SunnyFlail\Forms\Interfaces\IElement;

final class EmailField extends AbstractInputField
{

    public function __construct(
        string $name = "email",
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
            nestedElements: $nestedElements,
            constraints: [new EmailConstraint()]
        );
    }

    protected function getInputElement(): IElement
    {
        $attributes = $this->inputAttributes;
        $attributes['minlength'] = 5;
        $attributes['maxlength'] = 254;

        return new InputElement(
            id: $this->getInputId(),
            type: 'email',
            name: $this->name,
            attributes: $attributes
        );
    }

}