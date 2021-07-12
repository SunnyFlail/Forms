<?php

namespace SunnyFlail\Forms\Mappers;

use SunnyFlail\Forms\Interfaces\IFormElement;

final class ArrayMapper extends AbstractMapper
{

    public function scrapeForm(IFormElement $form, mixed &$vessel)
    {
        foreach ($form->getFields() as $name => $field) {
            $vessel[$name] = $this->getFieldValue($field);
        }
    }

}