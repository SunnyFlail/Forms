<?php

namespace SunnyFlail\Forms\Traits;

trait SingleValueFieldTrait
{

    use InputFieldTrait;

    public function getFullName(): string
    {
        return $this->form->getName() . '[' . $this->name . ']';
    }

}