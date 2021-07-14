<?php

namespace SunnyFlail\Forms\Fields;

use SunnyFlail\Forms\Traits\ResolveSelectTrait;

final class CheckBoxGroupField extends AbstractSelectableGroup
{
    use ResolveSelectTrait;

    public function __construct(
        string $name,
        array $options = [],
        bool $useIntristicValues = true,
        bool $multiple = true,
        array $constraints = [],
        array $nestedElements = [],
        protected array $inputAttributes = [],
        protected array $wrapperAttributes = [],
        protected array $labelAttributes = []
    ) {
        $this->name = $name;
        $this->valid = false;
        $this->error = null;
        $this->useIntristicValues = $useIntristicValues;
        $this->multiple = $multiple;
        $this->options = $options;
        $this->value = null;
        $this->radio = false;
        $this->nestedElements = $nestedElements;
        $this->constraints = $constraints;
    }

}