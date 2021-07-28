<?php

namespace SunnyFlail\Forms\Traits;

/**
 * Trait for Selectable Fields that can have multiple values
 */
trait MultipleValueSelectableTrait
{

    use SingleValueSelectableTrait, MultipleValueFieldTrait;

    public function resolve(array $values): bool
    {
        $value = $values[$this->name] ?? [];

        if (is_null($value) || empty($value)) {
            if ($this->required) {
                $this->error = $this->resolveErrorMessage("-1");

                return $this->valid = false;
            }
            $this->value = $this->multiple ? [] : null;

            return $this->valid = true;
        }

        if (is_array($value)) {
            if (!$this->multiple) {
                $this->error = $this->resolveErrorMessage("-1");

                return $this->valid = false;
            }

            return $this->valid = $this->resolveMultiple($value);
        }

        return $this->valid = $this->resolveSingular($value);
    }


    protected function resolveMultiple(array $values): bool
    {
        if ($this->useIntristicValues) {
            $values = array_intersect($values, $this->options);
        }

        if (!$values) {
            $this->error = $this->resolveErrorMessage('0');
            return  false;
        }

        if (!$this->useIntristicValues) {
            foreach ($values as $value) {
                if (true !== ($error = $this->checkConstraints($value))) {
                    $this->error = $error;
                    return false;
                }
            }
        }

        $this->value = $values;
        
        return true;
    }

}