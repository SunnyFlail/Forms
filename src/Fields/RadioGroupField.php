<?php

namespace SunnyFlail\Forms\Fields;

use SunnyFlail\Forms\Traits\SingularValueFieldTrait;

final class RadioGroupField extends AbstractSelectableGroup
{
    use SingularValueFieldTrait;

    public function __construct(
        string $name,
        array $options = [],
        bool $useIntristicValues = true,
        array $constraints = [],
        array $nestedElements = [],
        protected array $inputAttributes = [],
        protected array $wrapperAttributes = [],
        protected array $labelAttributes = [],
    ) {
        $this->name = $name;
        $this->valid = false;
        $this->useIntristicValues = $useIntristicValues;
        $this->options = $options;
        $this->error = null;
        $this->value = null;
        $this->radio = true;
        $this->nestedElements = $nestedElements;
        $this->constraints = $constraints;
    }

    public function resolve(array $values): bool
    {
        $value = $values[$this->name] ?? null;

        if ($value === null || is_array($value)) {
            if ($this->required) {
                $this->error = $this->resolveErrorMessage("-1");

                return false;
            }
            return $this->valid = true;
        }

        return $this->resolveSingular($value);
    }

}