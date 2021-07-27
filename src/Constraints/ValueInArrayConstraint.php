<?php

namespace SunnyFlail\Forms\Constraints;

use SunnyFlail\Forms\Interfaces\IConstraint;

final class ValueInArrayConstraint implements IConstraint
{

    /**
     * @param string[] $values
     */
    public function __construct(private array $values)
    {
        
    }

    public function matchesConstraint($value): bool
    {
        return in_array($value, $this->values);   
    }

}