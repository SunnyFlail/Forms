<?php

namespace SunnyFlail\Forms\Fields;

use SunnyFlail\HtmlAbstraction\Interfaces\IElement;
use SunnyFlail\HtmlAbstraction\Elements\InputElement;
use SunnyFlail\Forms\Constraints\EmailConstraint;

final class EmailField extends AbstractInputField
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
        array $wrapperAttributes = [],
        array $errorAttributes = [],
        ?string $labelText = null,
        array $labelAttributes = []
    ) {
        parent::__construct();

        $this->name = $name;
        $this->required = $required;
        $this->labelText = $labelText;
        $this->errorMessages = $errorMessages;
        $this->topElements = $topElements;
        $this->middleElements = $middleElements;
        $this->bottomElements = $bottomElements;
        $this->errorAttributes = $errorAttributes;
        $this->labelAttributes = $labelAttributes;
        $this->wrapperAttributes = $wrapperAttributes;
        $this->constraints = [new EmailConstraint()];
    }

    public function getInputElement(): IElement
    {
        $attributes = $this->inputAttributes;
        $attributes['minlength'] = 5;
        $attributes['maxlength'] = 254;
        $value = $this->rememberValue ? $this->value : null;

        return new InputElement(
            id: $this->getInputId(),
            type: 'email',
            name: $this->getFullName(),
            attributes: $attributes,
            value: $value
        );
    }

}