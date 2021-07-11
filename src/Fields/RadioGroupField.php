<?php

namespace SunnyFlail\Forms\Fields;

use SunnyFlail\Forms\Elements\CheckableElement;
use SunnyFlail\Forms\Elements\ContainerElement;
use SunnyFlail\Forms\Elements\LabelElement;
use SunnyFlail\Forms\Interfaces\IInputField;
use SunnyFlail\Forms\Interfaces\ISelectableField;
use SunnyFlail\Forms\Traits\ContainerElementTrait;
use SunnyFlail\Forms\Traits\FieldTrait;
use SunnyFlail\Forms\Traits\InputFieldTrait;
use SunnyFlail\Forms\Traits\SelectableTrait;

final class RadioGroupField implements ISelectableField, IInputField
{
    use ContainerElementTrait, FieldTrait, SelectableTrait, InputFieldTrait;

    public function __construct(
        protected string $name,
        protected array $options,
        protected array $inputAttributes = [],
        protected array $wrapperAttributes = [],
        protected array $labelAttributes = [],
        array $nestedElements = []
    ) {
        $this->error = null;
        $this->value = null;
        $this->
        $this->nestedElements = $nestedElements;
    }

    public function resolve(array $values): bool
    {
        $value = $values[$this->name] ?? null;

        if (in_array($value, $this->optons)) {
            $this->value = $value;
            $this->valid = true;
        }

        return $this->valid;
    }

    public function __toString(): string
    {
        $inputs = [];
        $name = $this->getFullName();

        foreach ($this->options as $label => $value) {
            if (is_numeric($label)) {
                $label = $value;
            }

            $checked = $this->value === $value;

            $inputs[] = new LabelElement(
                labelText: $label,
                attributes: $this->labelAttributes,
                nestedElements: [
                    new CheckableElement(
                        name: $name,
                        radio: true,
                        checked: $checked,
                        attributes: $this->inputAttributes
                    )
                ]
            );
        }

        return new ContainerElement(
            attributes: $this->containerAttributes,
            nestedElements: $inputs
        );
    }
    
}