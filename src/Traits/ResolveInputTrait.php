<?php

namespace SunnyFlail\Forms\Traits;

use SunnyFlail\Forms\Traits\SingleValueFieldTrait;
use SunnyFlail\Forms\Traits\ValidableFieldTrait;

/**
 * Trait for Fields with a basic, text input element
 */
trait ResolveInputTrait
{

    use SingleValueFieldTrait, ValidableFieldTrait;

    public function resolve(array $values): bool
    {
        $value = $values[$this->name] ?? null;
        
        if ($value === null) {
            if ($this->required) {
                $this->error = $this->resolveErrorMessage("-1");

                return $this->valid = false;
            }
            return $this->valid = true;
        }

        if (true !== ($error = $this->checkConstraints($value))) {
            $this->error = $error;
            return $this->valid = false;
        }

        $this->value = $value;
        return $this->valid = true;
    }

}