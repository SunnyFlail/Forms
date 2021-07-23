<?php

namespace SunnyFlail\Forms\Traits;

use SunnyFlail\Forms\Interfaces\IField;

/**
 * Trait for classes implementing IInputField interface
 */
trait InputFieldTrait
{

    use FieldTrait;

    /**
     * @var string $name Name of the input element
     */
    protected string $name;
    
    /**
     * @var mixed $value Value of the field
     */
    protected mixed $value;

    public function getName(): string
    {
        return $this->name;
    }

    public function getInputId(): string
    {
        return $this->form->getName() . "-"  . $this->name;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function withValue($value): IField
    {
        $this->value = $value;
        return $this;
    }

}