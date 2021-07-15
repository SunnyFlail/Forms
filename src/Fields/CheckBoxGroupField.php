<?php

namespace SunnyFlail\Forms\Fields;

use SunnyFlail\Forms\Traits\ResolveSelectTrait;

final class CheckBoxGroupField extends AbstractSelectableGroup
{
    use ResolveSelectTrait;

    public function __construct(
        string $name,
        array $options = [],
        bool $required = true,
        protected bool $rememberValue = true,
        bool $multiple = true,
        bool $useIntristicValues = true,
        array $constraints = [],
        array $errorMessages = [],
        array $nestedElements = [],
        protected array $inputAttributes = [],
        protected array $wrapperAttributes = [],
        protected array $labelAttributes = []
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
        $this->nestedElements = $nestedElements;
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