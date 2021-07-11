<?php

namespace SunnyFlail\Forms\Interfaces;

/**
 * Basic interface for Field Constraints
 */
interface IConstraint
{

    /**
     * Checks whether provided value fits with constraint
     * 
     * @return bool
     */
    public function formValueValid($value): bool;

}