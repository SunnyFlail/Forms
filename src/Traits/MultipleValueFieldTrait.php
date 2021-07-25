<?php

namespace SunnyFlail\Forms\Traits;

/**
 * Trait for fields that can have multiple values
 */
trait MultipleValueFieldTrait
{

    use InputFieldTrait;

    protected bool $multiple;

    public function getFullName(): string
    {
        $suffix = $this->multiple ? "[]" : '';

        return $this->form->getName() . '[' . $this->name . ']' . $suffix;
    }
    
}