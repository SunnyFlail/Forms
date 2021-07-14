<?php

namespace SunnyFlail\Forms\Traits;

use SunnyFlail\Forms\Interfaces\IField;

/**
 * Trait for classes implementing IInputField interface
 */
trait InputFieldTrait
{
    /**
     * @var string $name Name of the input element
     */
    protected string $name;
    /**
     * @var bool $required Boolean indicating whether this field is required
     */
    protected bool $required;
    /**
     * @var mixed $value Value of the field
     */
    protected mixed $value;

    public function getName(): string
    {
        return $this->name;
    }

    public function getFullName(): string
    {
        return $this->form->getName() . '[' . $this->name . ']';
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