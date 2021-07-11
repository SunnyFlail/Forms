<?php

namespace SunnyFlail\Forms\Traits;

use SunnyFlail\Forms\Interfaces\ISelectableField;

/**
 * Trait for classes implementing ISelectableField interface
 */
trait SelectableTrait
{

    public function withOptions(array $options): ISelectableField
    {
        $this->options = $options;
        return $this;
    }
    
}