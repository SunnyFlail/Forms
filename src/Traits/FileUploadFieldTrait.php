<?php

namespace SunnyFlail\Forms\Traits;

use SunnyFlail\Forms\Interfaces\IFileConstraint;
use Psr\Http\Message\UploadedFileInterface;

/**
 * Trait for classes implementing IFileField interface
 */
trait FileUploadFieldTrait
{

    use MultipleFieldNameTrait;
    /**
     * @var IFileConstraint[] $constraints
     */
    protected array $constraints = [];

    public function resolve(array $params): bool
    {
        /**
         * @var UploadedFileInterface[]$files
         */
        $files = $params[$this->name] ?? null;

        if ((!$files) || ($this->multiple && !is_array($files))
            || (!$this->multiple && !is_object($files) && !($files instanceof UploadedFileInterface))
        ) {
            if ($this->required) {
                $this->error = $this->resolveErrorMessage('-1');

                return false;
            }
            return $this->valid = true;
        }

        if ($this->multiple) {
            return $this->checkMultipleFiles($files);
        }

        return $this->checkSingleFile($files);
    }

    protected function checkSingleFile(UploadedFileInterface $file) {
        foreach ($this->constraints as $errorKey => $constraint) {
            if (!$constraint->fileValid($file)) {
                $this->error = $this->resolveErrorMessage("$errorKey");

                return false;
            }
        }

        $this->value = $file;

        return $this->valid = true;
    }

    protected function checkMultipleFiles(array $files): bool
    {
        $incorrectFiles = [];

        foreach ($this->constraints as $errorKey => $constraint) {
            foreach ($files as $fileKey => $file) {
                /**
                 * Skip incorrect files
                 */
                if (array_key_exists($fileKey, $incorrectFiles)) {
                    continue;
                }

                if (!$constraint->fileValid($file)) {
                    $this->error = $this->resolveErrorMessage("$errorKey");

                    if ($this->terminateOnError) {
                        return false;
                    }

                    $incorrectFiles[$fileKey] = true;
                    continue;
                }
            }
        }
        $this->value = array_diff_key(
            $files, $incorrectFiles
        );

        return $this->valid = true;
    }

    protected function resolveErrorMessage(string $code): string
    {
        if (!isset($this->errorMessages[$code])) {
            switch ($code) {
            case "-1":
                return "No files were uploaded!";
            default:
                return "One or more of uploaded files doesn't match constraints!";
            }
        }

        return $this->errorMessages[$code];
    }

}