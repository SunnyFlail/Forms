<?php

namespace SunnyFlail\Forms\Fields;

use Psr\Http\Message\UploadedFileInterface;
use SunnyFlail\Forms\Interfaces\IContainerField;
use SunnyFlail\HtmlAbstraction\Elements\ContainerElement;
use SunnyFlail\HtmlAbstraction\Elements\FileElement;
use SunnyFlail\Forms\Interfaces\IFileConstraint;
use SunnyFlail\Forms\Interfaces\IInputField;
use SunnyFlail\Forms\Interfaces\IFileField;
use SunnyFlail\Forms\Traits\ContainerFieldTrait;
use SunnyFlail\Forms\Traits\LabeledElementTrait;
use SunnyFlail\Forms\Traits\MultipleFieldNameTrait;
use SunnyFlail\HtmlAbstraction\Interfaces\IElement;

final class FileUploadField implements IInputField, IFileField, IContainerField
{
    
    use MultipleFieldNameTrait, LabeledElementTrait, ContainerFieldTrait;

    /**
     * @param IFileConstraint[] $constraints
     */
    public function __construct(
        string $name,
        bool $required = true,
        bool $multiple = true,
        protected array $constraints = [],
        array $topElements = [],
        array $middleElements = [],
        array $bottomElements = [],
        array $errorMessages = [],
        protected array $wrapperAttributes = [],
        array $labelAttributes = [],
        ?string $labelText = null,
        protected array $inputAttributes = [],
        protected bool $terminateOnError = false
    ) {
        $this->valid = false;
        $this->error = null;
        $this->value = null;
        $this->name = $name;
        $this->required = $required;
        $this->multiple = $multiple;
        $this->labelText = $labelText;
        $this->topElements = $topElements;
        $this->middleElements = $middleElements;
        $this->bottomElements = $bottomElements;
        $this->labelAttributes = $labelAttributes;
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
        return new ContainerElement(
            attributes: $this->wrapperAttributes,
            nestedElements: [
                ...$this->topElements,
                $this->getLabelElement(),
                ...$this->middleElements,
                $this->getInputElement(),
                ...$this->bottomElements,
                $this->getErrorElement()
            ]
        );
    }

    public function getInputElement(): IElement|array
    {
        return new FileElement(
            name: $this->getFullName(),
            id: $this->getInputId(),
            multiple: $this->multiple,
            attributes: $this->inputAttributes
        );
    }

}