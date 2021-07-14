<?php

namespace SunnyFlail\Forms\Fields;

use SunnyFlail\HtmlAbstraction\Traits\ContainerElementTrait;
use SunnyFlail\HtmlAbstraction\Elements\CheckableElement;
use SunnyFlail\HtmlAbstraction\Elements\ContainerElement;
use SunnyFlail\HtmlAbstraction\Elements\LabelElement;
use SunnyFlail\Forms\Interfaces\ISelectableField;
use SunnyFlail\Forms\Interfaces\IInputField;
use SunnyFlail\Forms\Traits\InputFieldTrait;
use SunnyFlail\Forms\Traits\SelectableTrait;
use SunnyFlail\Forms\Traits\FieldTrait;

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
        $this->valid = false;
        $this->error = null;
        $this->value = null;
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
        $baseId = $this->getInputId();

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
                        id: $this->resolveId($baseId, $value),
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
    
    private function resolveId(string $baseId, string $value)
    {
        $value = strtr($value, " ", "_");
        return $baseId . '--' . $value;
    }

}