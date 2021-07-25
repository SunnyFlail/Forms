<?php

namespace SunnyFlail\Forms\Traits;

trait MultipleFieldNameTrait
{

    use InputFieldTrait;

    protected bool $multiple;

    public function getFullName(): string
    {
        $suffix = $this->multiple ? "[]" : '';

        return $this->form->getName() . '[' . $this->name . ']'. $suffix;
    }
    
}