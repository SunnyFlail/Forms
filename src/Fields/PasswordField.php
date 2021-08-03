<?php

namespace SunnyFlail\Forms\Fields;

use SunnyFlail\HtmlAbstraction\Elements\ContainerElement;
use SunnyFlail\HtmlAbstraction\Elements\ButtonElement;
use SunnyFlail\HtmlAbstraction\Elements\InputElement;
use SunnyFlail\HtmlAbstraction\Interfaces\IElement;

final class PasswordField extends InputField
{
    protected string $type;

    public function __construct(
        string $name,
        bool $required = true,
        protected bool $rememberValue = true,
        array $constraints = [],
        array $errorMessages = [],
        array $topElements = [],
        array $middleElements = [],
        array $bottomElements = [],
        array $inputAttributes = [],
        protected bool $withPeeper = true,
        protected array $peeperAttributes = [],
        array $containerAttributes = [],
        array $errorAttributes = [],
        ?string $labelText = null,
        array $labelAttributes = []
    ) {
        parent::__construct(
            name: $name,
            type: 'password',
            required: $required,
            labelText: $labelText,
            constraints: $constraints,
            topElements: $topElements,
            middleElements: $middleElements,
            bottomElements: $bottomElements,
            errorMessages: $errorMessages,
            errorAttributes: $errorAttributes,
            inputAttributes: $inputAttributes,
            labelAttributes: $labelAttributes,
            containerAttributes: $containerAttributes
        );
    }

    public function getInputElement(): IElement
    {
        $id = $this->getInputId();
        $name = $this->getFullName();
        $value = $this->rememberValue ? $this->value : null;

        if ($this->withPeeper) {
            return new ContainerElement(
                attributes: [
                    'style' => 'position: relative;'
                ],
                nestedElements: [
                    new InputElement(
                        id: $id,
                        type: $this->type,
                        name: $name,
                        attributes: $this->inputAttributes,
                        value: $value
                    ),
                    new ButtonElement(
                        type: 'button',
                        attributes: $this->peeperAttributes
                    )
                ]
            );
        }

        return new InputElement(
            id: $id,
            type: $this->type,
            name: $name,
            attributes: $this->inputAttributes,
            value: $value
        );
    }

}