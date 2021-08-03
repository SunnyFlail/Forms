<?php

namespace SunnyFlail\Forms\Fields;

use SunnyFlail\Forms\Traits\MultipleValueSelectableTrait;

/**
 * Field containing checkboxes
 */
final class CheckBoxGroupField extends AbstractSelectableGroup
{
    use MultipleValueSelectableTrait;

    public function __construct(
        string $name,
        array $options = [],
        bool $required = true,
        bool $rememberValue = true,
        bool $multiple = true,
        bool $useIntristicValues = true,
        array $constraints = [],
        array $errorMessages = [],
        array $inputAttributes = [],
        array $containerAttributes = [],
        array $labelAttributes = []
    ) {

        $this->radio = false;
        $this->name = $name;
        $this->options = $options;
        $this->multiple = $multiple;
        $this->required = $required;
        $this->constraints = $constraints;
        $this->errorMessages = $errorMessages;
        $this->rememberValue = $rememberValue;
        $this->labelAttributes = $labelAttributes;
        $this->inputAttributes = $inputAttributes;
        $this->containerAttributes = $containerAttributes;
        $this->useIntristicValues = $useIntristicValues;
    }

    protected function isChecked(string $value): bool
    {
        if (!$this->rememberValue) {
            return false;
        }

        if ($this->multiple) {
            return in_array($value, $this->value);
        }

        return ($value === $this->value);
    }

}