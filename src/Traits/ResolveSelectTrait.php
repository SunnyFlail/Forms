<?php

namespace SunnyFlail\Forms\Traits;

trait ResolveSelectTrait
{

    use SingularValueFieldTrait;

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
 
        if (is_array($value)) {
            if (!$this->multiple) {
                $this->error = $this->resolveErrorMessage("-1");

                return false;
            }

            return $this->resolveMultiple($value);
        }

        return $this->resolveSingular($value);
    }


    protected function resolveMultiple(array $values): bool
    {
        if ($this->useIntristicValues) {
            $values = array_intersect($values, $this->option);
        }

        if (!$values) {
            $this->error = $this->resolveErrorMessage('0');
            return false;
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
        
        return $this->valid = true;
    }

    public function getFullName(): string
    {
        $suffix = $this->multiple ? "" : '[]';

        return $this->form->getName() . '[' . $this->name . ']' . $suffix;
    }

}