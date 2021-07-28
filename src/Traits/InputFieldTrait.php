<?php

namespace SunnyFlail\Forms\Traits;

use SunnyFlail\Forms\Interfaces\IField;

/**
 * Trait for classes implementing IInputField interface
 */
trait InputFieldTrait
{

    use IdentifableFieldTrait;

    /**
     * @var mixed $value Value of the field
     */
    protected mixed $value;

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function withValue($value): IField
    {
        $this->value = $value;
        return $this;
    }

}