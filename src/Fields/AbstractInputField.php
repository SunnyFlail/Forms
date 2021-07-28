<?php

namespace SunnyFlail\Forms\Fields;

use SunnyFlail\Forms\Interfaces\IInputField;
use SunnyFlail\Forms\Traits\ValidableFieldTrait;
use SunnyFlail\Forms\Traits\WrapperFieldTrait;
use SunnyFlail\Forms\Traits\SingleInputFieldTrait;
use SunnyFlail\Forms\Traits\SingleValueFieldTrait;

abstract class AbstractInputField implements IInputField
{

    use ValidableFieldTrait, WrapperFieldTrait, SingleInputFieldTrait, SingleValueFieldTrait;

    protected array $wrapperAttributes;

    public function __construct()
    {
        $this->valid = null;
        $this->error = null;
        $this->value = null;
    }

    public function resolve(array $values): bool
    {
        $value = $values[$this->name] ?? null;
        
        if ($value === null) {
            if ($this->required) {
                $this->error = $this->resolveErrorMessage("-1");

                return false;
            }
            return $this->valid = true;
        }

        if (true !== ($error = $this->checkConstraints($value))) {
            $this->error = $error;
            return false;
        }

        $this->value = $value;
        return $this->valid = true;
    }

}