<?php

namespace SunnyFlail\Forms\Fields;

use SunnyFlail\Forms\Interfaces\IInputField;
use SunnyFlail\Forms\Traits\InputFieldTrait;
use SunnyFlail\Forms\Traits\SingleElementFieldTrait;
use SunnyFlail\Forms\Traits\SingleValueFieldTrait;
use SunnyFlail\Forms\Traits\WrapperFieldTrait;
use SunnyFlail\HtmlAbstraction\Elements\CheckableElement;
use SunnyFlail\HtmlAbstraction\Interfaces\IElement;

final class CheckboxField extends AbstractInputField
{

    protected array $containerAttributes;

    public function __construct(
        string $name,
        bool $required = true,
        protected bool $rememberValue = true,
        protected string $requiredMessage = 'This field must be filled!',
        array $topElements = [],
        array $middleElements = [],
        array $bottomElements = [],
        protected array $inputAttributes = [],
        array $containerAttributes = [],
        array $errorAttributes = [],
        ?string $labelText = null,
        array $labelAttributes = []
    ) {
        parent::__construct();

        $this->name = $name;
        $this->required = $required;
        $this->labelText = $labelText;
        $this->topElements = $topElements;
        $this->middleElements = $middleElements;
        $this->bottomElements = $bottomElements;
        $this->errorAttributes = $errorAttributes;
        $this->labelAttributes = $labelAttributes;
        $this->containerAttributes = $containerAttributes;
    }

    public function resolve(array $values): bool
    {
        if (!isset($values[$this->name])) {
            if ($this->required) {
                $this->error = $this->requiredMessage;
                return $this->valid = false;
            }

            $this->value = false;
            return $this->valid = true;
        }

        $this->value = true;
        return $this->valid = true;
    }

    public function getInputElement(): IElement|array
    {
        return new CheckableElement(
            id: $this->getInputId(),
            name: $this->getFullName(),
            attributes: $this->inputAttributes,
            checked: $this->rememberValue ? boolval($this->value) : false
        );
    }

    public function jsonSerialize()
    {
        $attributes = $this->inputAttributes;
        $attributes['type'] = 'checkbox';
        $attributes['checked'] = $this->value ? true : false;

        return [
            'tagName' => 'INPUT',
            'name' => $this->getFullName(),
            'id' => $this->getInputId(),
            'required' => $this->required,
            'valid' => $this->valid,
            'label' => $this->labelText ?? $this->name,
            'value' => $this->value,
            'error' => $this->error,
            'attributes' => $attributes
        ];
    }

}