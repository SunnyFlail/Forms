<?php

namespace SunnyFlail\Forms\FormBuilder;

use SunnyFlail\Forms\Exceptions\FormBuilderException;
use SunnyFlail\Forms\Interfaces\IFormElement;
use SunnyFlail\Forms\Interfaces\IValueProvider;
use ReflectionObject;

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
