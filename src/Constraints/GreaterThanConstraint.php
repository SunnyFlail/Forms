<?php

namespace SunnyFlail\Forms\Constraints;

use SunnyFlail\Forms\Interfaces\IConstraint;

final class GreaterThanConstraint implements IConstraint
{

    public function __construct(private int|float $min)
    {
    }

    public function matchesConstraint($value): bool
    {
        if (!is_numeric($value)) {
            return false;
        }

        if ($value < $this->min) {
            return false;
        }

        return true;
    }

}