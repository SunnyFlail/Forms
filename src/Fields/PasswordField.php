<?php

namespace SunnyFlail\Forms\Fields;

use SunnyFlail\HtmlAbstraction\Elements\ContainerElement;
use SunnyFlail\HtmlAbstraction\Elements\ButtonElement;
use SunnyFlail\HtmlAbstraction\Elements\InputElement;
use SunnyFlail\HtmlAbstraction\Interfaces\IElement;

final class PasswordField extends AbstractInputField
{

    protected string $type;

    public function __construct(
        string $name,
        bool $required = true,
        array $errorMessages = [],
        array $constraints = [],
        array $nestedElements = [],
        protected bool $withPeeper = true,
        protected array $inputAttributes = [],
        protected array $peeperAttributes = [],
        array $wrapperAttributes = [],
        array $errorAttributes = [],
        ?string $labelText = null,
        array $labelAttributes = []
    ) {
        parent::__construct();

        $this->name = $name;
        $this->required = $required;
        $this->type = "password";
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