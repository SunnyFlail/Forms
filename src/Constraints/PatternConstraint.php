<?php

namespace SunnyFlail\Forms\Constraints;

use SunnyFlail\Forms\Interfaces\IConstraint;

class PatternConstraint implements IConstraint
{

    /**
     * @var string[] $regexes
     */
    private array $regexes;

    public function __construct(string ...$regexes)
    {
        $this->regexes = $regexes;
    }

    public function formValueValid($value): bool
    {
        if (!is_string($value) && !is_numeric($value)) {
            return false;
        }

        foreach ($this->regexes as $regex) {
            if (1 !== preg_match($regex, $value)) {
                return false;
            }
        }
        return true;
    }

}