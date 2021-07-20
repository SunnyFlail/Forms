<?php

namespace SunnyFlail\Forms\Fields;

use SunnyFlail\HtmlAbstraction\Traits\ContainerElementTrait;
use SunnyFlail\HtmlAbstraction\Elements\ContainerElement;
use SunnyFlail\HtmlAbstraction\Elements\TextNodeElement;
use SunnyFlail\HtmlAbstraction\Elements\LabelElement;
use SunnyFlail\HtmlAbstraction\Interfaces\IContainerElement;
use SunnyFlail\HtmlAbstraction\Interfaces\IElement;
use SunnyFlail\Forms\Traits\ValidableFieldTrait;
use SunnyFlail\Forms\Traits\FieldTrait;
use SunnyFlail\Forms\Interfaces\IInputField;
use SunnyFlail\Forms\Traits\ContainerFieldTrait;
use SunnyFlail\Forms\Traits\LabeledElementTrait;

abstract class AbstractInputField implements IInputField
{

    use FieldTrait, ValidableFieldTrait, LabeledElementTrait, ContainerFieldTrait;

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