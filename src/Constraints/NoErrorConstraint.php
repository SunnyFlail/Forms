<?php

namespace SunnyFlail\Forms\Constraints;

use Psr\Http\Message\UploadedFileInterface;
use SunnyFlail\Forms\Interfaces\IFileConstraint;

final class NoErrorConstraint implements IFileConstraint
{

    public function __construct()
    {
    }

    public function fileValid(UploadedFileInterface $file): bool
    {
        return ($file->getError() === UPLOAD_ERR_OK);
    }

}