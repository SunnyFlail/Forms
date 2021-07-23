<?php

namespace SunnyFlail\Forms\Fields;

use SunnyFlail\HtmlAbstraction\Elements\ContainerElement;
use SunnyFlail\Forms\Interfaces\IInputField;
use SunnyFlail\Forms\Traits\FieldTrait;
use SunnyFlail\Forms\Traits\ValidableFieldTrait;
use SunnyFlail\Forms\Traits\ContainerFieldTrait;
use SunnyFlail\Forms\Traits\LabeledElementTrait;
use SunnyFlail\Forms\Traits\SingularFieldNameTrait;

abstract class AbstractInputField implements IInputField
{

    use FieldTrait, ValidableFieldTrait, LabeledElementTrait, ContainerFieldTrait, SingularFieldNameTrait;

    protected array $wrapperAttributes;

    public function __construct()
    {
        $this->valid = false;
        $this->error = null;
        $this->value = null;
    }

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

        if (true !== ($error = $this->checkConstraints($value))) {
            $this->error = $error;
            return false;
        }

        $this->value = $value;
        return $this->valid = true;
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

}