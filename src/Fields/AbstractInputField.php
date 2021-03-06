<?php

namespace SunnyFlail\Forms\Fields;

use SunnyFlail\Forms\Interfaces\IInputField;
use SunnyFlail\Forms\Traits\ValidableFieldTrait;
use SunnyFlail\Forms\Traits\WrapperFieldTrait;
use SunnyFlail\Forms\Traits\SingleElementFieldTrait;
use SunnyFlail\Forms\Traits\SingleValueFieldTrait;

abstract class AbstractInputField implements IInputField
{

    use SingleElementFieldTrait, SingleValueFieldTrait;

    protected array $containerAttributes;

    public function __construct()
    {
        $this->valid = null;
        $this->error = null;
        $this->value = null;
    }

}