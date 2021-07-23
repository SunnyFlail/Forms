<?php

namespace SunnyFlail\Forms\Traits;

trait SingularFieldNameTrait
{

    public function getFullName(): string
    {
        return $this->form->getName() . '[' . $this->name . ']';
    }

}