<?php

namespace SunnyFlail\Forms\Fields;

use Psr\Http\Message\UploadedFileInterface;
use SunnyFlail\HtmlAbstraction\Elements\ContainerElement;
use SunnyFlail\HtmlAbstraction\Elements\LabelElement;
use SunnyFlail\HtmlAbstraction\Elements\FileElement;
use SunnyFlail\Forms\Interfaces\IFileConstraint;
use SunnyFlail\Forms\Interfaces\IInputField;
use SunnyFlail\Forms\Interfaces\IFileField;
use SunnyFlail\Forms\Traits\InputFieldTrait;
use SunnyFlail\Forms\Traits\FieldTrait;

final class FileUploadField implements IInputField, IFileField
{
    
    use InputFieldTrait, FieldTrait;

    /**
     * @param IFileConstraint[] $constraints
     * */
    public function __construct(
        string $name,
        bool $required = true,
        protected bool $multiple = true,
        protected array $constraints = [],
        array $errorMessages = [],
        protected array $wrapperAttributes = [],
        protected array $labelAttributes = [],
        protected ?string $labelText = null,
        protected array $inputAttributes = [],
        protected bool $terminateOnError = false
    ) {
        $this->valid = false;
        $this->error = null;
        $this->value = null;
        $this->name = $name;
        $this->required = $required;
        $this->errorMessages = $errorMessages;
    }

    public function resolve(array $params): bool
    {
        /** @var UploadedFileInterface[] $files */
        $files = $params[$this->name] ?? null;

        if ($files === null) {
            if ($this->required) {
                $this->error = $this->resolveErrorMessage('-1');

                return false;
            }
            return $this->valid = true;
        }

        $incorrectFiles = [];

        foreach ($this->constraints as $errorKey => $constraint) {
            foreach ($files as $fileKey => $file) {
                /** Skip incorrect files */
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

    public function getFullName(): string
    {
        $suffix = $this->multiple ? "" : '[]';

        return $this->name . $suffix;
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

    public function __toString(): string
    {
        $inputId = $this->getInputId();

        return new ContainerElement(
            attributes: $this->wrapperAttributes,
            nestedElements: [
                new LabelElement(
                    for: $inputId,
                    labelText: $this->labelText,
                    attributes: $this->labelAttributes
                ),
                new FileElement(
                    name: $this->getFullName(),
                    id: $inputId,
                    multiple: $this->multiple,
                    attributes: $this->inputAttributes
                )
            ]
        );
    }

}