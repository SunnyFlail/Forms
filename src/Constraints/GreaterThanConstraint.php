<?php

namespace SunnyFlail\Forms\Constraints;

use SunnyFlail\Forms\Interfaces\IConstraint;

final class GreaterThanConstraint implements IConstraint
{

    public function __construct(
        private int|float $min,
        private bool $orEqual = false
    ) {}

    public function matchesConstraint($value): bool
    {
        if (!is_numeric($value)) {
            return false;
        }

        if ($this->orEqual) {
            return ($value >= $this->min);
        }

        return ($value > $this->min);
    }

}