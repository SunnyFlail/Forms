<?php

namespace SunnyFlail\Forms\Exceptions;

use InvalidArgumentException;
use Throwable;

final class FormNotFoundException extends InvalidArgumentException
{

    public function __construct(string $classFQCN, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(
            sprintf("Form %s not found or doesn't implement IFormElement interface!", $classFQCN), $code, $previous
        );
    }

}