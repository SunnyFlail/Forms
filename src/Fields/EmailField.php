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
        $this->errorMessages = $errorMessages;
        $this->wrapperAttributes = $wrapperAttributes;
        $this->errorAttributes = $errorAttributes;
        $this->labelText = $labelText;
        $this->labelAttributes = $labelAttributes;
        $this->nestedElements = $nestedElements;
        $this->constraints = [new EmailConstraint()];
    }


    protected function getInputElement(): IElement
    {
        $attributes = $this->inputAttributes;
        $attributes['minlength'] = 5;
        $attributes['maxlength'] = 254;

        return new InputElement(
            id: $this->getInputId(),
            type: 'email',
            name: $this->getFullName(),
            attributes: $attributes
        );
    }

}