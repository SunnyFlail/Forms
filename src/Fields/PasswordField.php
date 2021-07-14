<?php

namespace SunnyFlail\Forms\Fields;

use SunnyFlail\HtmlAbstraction\Elements\ContainerElement;
use SunnyFlail\HtmlAbstraction\Elements\ButtonElement;
use SunnyFlail\HtmlAbstraction\Elements\InputElement;
use SunnyFlail\HtmlAbstraction\Interfaces\IElement;

final class PasswordField extends AbstractInputField
{

    public function __construct(
        string $name = "password",
        bool $required = true,
        array $errorMessages = [],
        protected bool $withPeeper = true,
        protected array $peeperAttributes = [],
        protected array $inputAttributes = [],
        array $wrapperAttributes = [],
        array $errorAttributes = [],
        ?string $labelText = null,
        array $labelAttributes = [],
        array $nestedElements = [],
        array $constraints = []
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
            constraints: $constraints
        );
    }

    protected function getInputElement(): IElement
    {
        $attributes = $this->inputAttributes;
        if ($this->valid) {
            $attributes["value"] = $this->value;
        }
        $id = $this->getInputId();
        $name = $this->getFullName();

        if ($this->withPeeper) {
            return new ContainerElement(
                attributes: [
                    "style" => "position: relative;"
                ],
                nestedElements: [
                    new InputElement(
                        id: $id,
                        type: "password",
                        name: $name,
                        attributes: $attributes
                    ),
                    new ButtonElement(
                        type: "button",
                        attributes: $this->peeperAttributes
                    )
                ]
            );
        }

        return new InputElement(
            id: $id,
            type: "password",
            name: $name,
            attributes: $attributes
        );
    }

}