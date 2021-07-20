<?php

namespace SunnyFlail\Forms\Constraints;

use SunnyFlail\Forms\Interfaces\IConstraint;

final class OneOfConstraint implements IConstraint
{

    /**
     * @var IConstraint[] $constraints
     */
    private array $constraints;

    public function __construct(
        IConstraint ...$constraints
    ) {
        $this->constraints = $constraints;
    }

    public function matchesConstraint($value): bool
    {
        foreach ($this->constraints as $constraint) {
            if ($constraint->matchesConstraint($value)) {
                return true;
            }
        }
        return false;
    }

}