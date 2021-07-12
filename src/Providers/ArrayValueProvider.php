<?php

namespace SunnyFlail\Forms\FormBuilder;

use SunnyFlail\Forms\Interfaces\IFormElement;
use SunnyFlail\Forms\Interfaces\IValueProvider;

final class ArrayValueProvider implements IValueProvider
{
    
    public function fill(IFormElement $form, $values)
    {
        foreach ($values as $name => $value) {
            if ($form->hasField($name)) {
                $form->withFieldValue($name, $value);
            }
        }
    }

}
