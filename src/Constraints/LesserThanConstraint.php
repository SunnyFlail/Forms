<?php

namespace SunnyFlail\Forms\Constraints;

use SunnyFlail\Forms\Interfaces\IConstraint;

final class LesserThanConstraint implements IConstraint
{

    public function __construct(
        private int|float $max,
        private bool $orEqual = false
    ) {}

    public function matchesConstraint($value): bool
    {
        if (!is_numeric($value)) {
            return false;
        }

        if ($this->orEqual) {
            return ($value <= $this->min);
        }

        return ($value < $this->max);
    }

}