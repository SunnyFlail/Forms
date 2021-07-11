<?php

namespace SunnyFlail\Forms\Providers;

use ReflectionObject;
use SunnyFlail\Forms\Interfaces\IFormElement;
use SunnyFlail\Forms\Interfaces\IValueProvider;

final class ObjectValueProvider implements IValueProvider
{

    public function fill(IFormElement $form, $values)
    {
        $reflection = new ReflectionObject($values);

        foreach ($reflection->getProperties() as $property) {
            $property->setAccessible(true);
            $name = $property->getName();
            
            if ($form->hasField($name)) {
                $value = $property->getValue($form);
                $form->withFieldValue($name, $value);
            }
        }
    }

}