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
        protected bool $rememberValue = true,
        array $constraints = [],
        array $errorMessages = [],
        array $topElements = [],
        array $middleElements = [],
        array $bottomElements = [],
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
        $this->type = "password";
        $this->required = $required;
        $this->labelText = $labelText;
        $this->constraints = $constraints;
        $this->topElements = $topElements;
        $this->middleElements = $middleElements;
        $this->bottomElements = $middleElements;
        $this->errorMessages = $errorMessages;
        $this->errorAttributes = $errorAttributes;
        $this->labelAttributes = $labelAttributes;
        $this->wrapperAttributes = $wrapperAttributes;
    }

    public function getInputElement(): IElement
    {
        $attributes = $this->inputAttributes;
        if ($this->valid) {
            $attributes["value"] = $this->value;
        }
        $id = $this->getInputId();
        $name = $this->getFullName();
        $value = $this->rememberValue ? $this->value : null;

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
                        attributes: $attributes,
                        value: $value
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
            attributes: $attributes,
            value: $value
        );
    }

}