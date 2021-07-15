<?php

namespace SunnyFlail\Forms\Traits;

trait SingularValueFieldTrait
{

    use ValidableFieldTrait;

    /**
     * @var bool $useIntristicValues Should this form be validated only by using preset values
     */
    protected bool $useIntristicValues;

    /**
     * @var string[] Preset values
     */
    protected array $options;

    protected function resolveSingular(mixed $value): bool
    {
        if ($this->useIntristicValues && !in_array($value, $this->options)) {
            $this->error = $this->resolveErrorMessage('0');

            return false;
        }

        if (!$this->useIntristicValues && true !== ($error = $this->checkConstraints($value))) {
            $this->error = $error;
            return false;
        }

        $this->value = $value;

        return $this->valid = true;
    }

}