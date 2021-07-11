<?php

namespace SunnyFlail\Forms\Fields;

use SunnyFlail\Forms\Elements\ContainerElement;
use SunnyFlail\Forms\Elements\ButtonElement;
use SunnyFlail\Forms\Elements\InputElement;
use SunnyFlail\Forms\Interfaces\IElement;

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

        if ($this->withPeeper) {
            $this->peeperAttributes["data-button-type"] = "peeper";
        }
    }

    protected function getInputElement(): IElement
    {
        $attributes = $this->inputAttributes;
        if ($this->valid) {
            $attributes["value"] = $this->value;
        }
        $id = $this->getInputId();
        if ($this->withPeeper) {
            return new ContainerElement(
                attributes: [
                    "style" => "position: relative;"
                ],
                nestedElements: [
                    new InputElement(
                        id: $id,
                        type: "password",
                        name: $this->name,
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
            name: $this->name,
            attributes: $attributes
        );
    }

}