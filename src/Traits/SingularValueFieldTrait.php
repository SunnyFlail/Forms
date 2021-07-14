<?php

namespace SunnyFlail\Forms\Traits;

trait SingularValueFieldTrait
{

    use ValidableFieldTrait;

    protected bool $useIntristicValues;

    protected array $options;

    protected function resolveSingular(mixed $value): bool
    {
        if ($this->useIntristicValues && !in_array($value, $this->options)) {
            $this->error = $this->resolveErrorMessage('0');

            return false;
        }

        if (true !== ($error = $this->checkConstraints($value))) {
            $this->error = $error;
            return false;
        }

        $this->value = $value;

        return $this->valid = true;
    }

}