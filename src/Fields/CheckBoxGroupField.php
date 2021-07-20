<?php

namespace SunnyFlail\Forms\Fields;

use SunnyFlail\Forms\Traits\MultipleValueFieldTrait;

/**
 * Field containing checkboxes
 */
final class CheckBoxGroupField extends AbstractSelectableGroup
{
    use MultipleValueFieldTrait;

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
        array $wrapperAttributes = [],
        array $labelAttributes = []
    ) {
        $this->value = null;
        $this->error = null;
        $this->valid = false;
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
        $this->wrapperAttributes = $wrapperAttributes;
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