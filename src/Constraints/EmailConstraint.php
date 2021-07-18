<?php

namespace SunnyFlail\Forms\Constraints;

use SunnyFlail\Forms\Interfaces\IConstraint;

final class EmailConstraint implements IConstraint
{

    public function matchesConstraint($value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        $length = strlen($value);
        if ($length < 5 || $length > 254) {
            return false;
        }

        if (1 === preg_match("/^[a-z0-9_][a-z0-9.\-+!#$%&'*+-\/=?^_`{|}~]+[a-z0-9_]\@[a-z0-9_]([a-z0-9_.\-]*[a-z0-9])?[.][a-z]+$/i", $value)) {
            return !strpos($value, "..");
        }

        return false;
    }

}