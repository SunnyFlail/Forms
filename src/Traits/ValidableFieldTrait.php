<?php

namespace SunnyFlail\Forms\Traits;

use SunnyFlail\Forms\Interfaces\IConstraint;

trait ValidableFieldTrait
{

    use InputFieldTrait;

    /**
     * @var IConstraint[] $constraints
     */   
    protected array $constraints = [];
    /**
     * @var string[] $errorMessages Messages to display if a constraint fails.
     */
    protected array $errorMessages = [];

    /**
     * Checks whether user-provided value is valid
     * 
     * @param mixed $value
     * 
     * @return true|string Returns error message or true if it passes
     */
    protected function checkConstraints(mixed $value): string|bool
    {
        if ($value === null) {
            if ($this->isRequired()) {
                return $this->resolveErrorMessage("-1");
            }
        }

        foreach ($this->constraints as $index => $constraint) {
            if (false === $constraint->matchesConstraint($value)) {
                return $this->resolveErrorMessage("$index");
            }
        }

        return true;
    }

    /**
     * Returns the error message with provided code;
     * 
     * @return string
     */
    protected function resolveErrorMessage(string $code): string
    {
        if (!isset($this->errorMessages[$code])) {
            if ($code === '-1') {
                return 'This field must be filled!';
            }
            
            return "Value doesn't fit in with constraints!";
        }

        return $this->errorMessages[$code];
    }

}