<?php

namespace SunnyFlail\Forms\Fields;

use SunnyFlail\Forms\Traits\SingleElementFieldTrait;
use SunnyFlail\Forms\Traits\SingleValueFieldTrait;
use SunnyFlail\Forms\Traits\SingleValueSelectableTrait;

final class RadioGroupField extends AbstractSelectableGroup
{
    use SingleValueSelectableTrait, SingleValueFieldTrait;

    public function __construct(
        string $name,
        array $options = [],
        bool $required = true,
        protected bool $rememberValue = true,
        bool $useIntristicValues = true,
        array $constraints = [],
        array $errorMessages = [],
        array $nestedElements = [],
        protected array $inputAttributes = [],
        protected array $wrapperAttributes = [],
        protected array $labelAttributes = [],
    ) {
        $this->error = null;
        $this->value = null;
        $this->radio = true;
        $this->valid = null;
        $this->name = $name;
        $this->options = $options;
        $this->required = $required;
        $this->constraints = $constraints;
        $this->errorMessages = $errorMessages;
        $this->nestedElements = $nestedElements;
        $this->useIntristicValues = $useIntristicValues;
    }

    public function resolve(array $values): bool
    {
        $value = $values[$this->name] ?? null;

        if ($value === null || is_array($value)) {
            if ($this->required) {
                $this->error = $this->resolveErrorMessage("-1");

                return $this->valid = false;
            }
            return $this->valid = true;
        }

        return $this->valid = $this->resolveSingular($value);
    }

    protected function isChecked(string $value): bool
    {
        if (!$this->rememberValue) {
            return false;
        }

        return ($value === $this->value);
    }

}