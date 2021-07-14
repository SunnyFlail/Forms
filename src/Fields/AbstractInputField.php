<?php

namespace SunnyFlail\Forms\Fields;

use SunnyFlail\HtmlAbstraction\Traits\ContainerElementTrait;
use SunnyFlail\HtmlAbstraction\Elements\ContainerElement;
use SunnyFlail\HtmlAbstraction\Elements\TextNodeElement;
use SunnyFlail\HtmlAbstraction\Elements\LabelElement;
use SunnyFlail\HtmlAbstraction\Interfaces\IElement;
use SunnyFlail\Forms\Interfaces\IConstraint;
use SunnyFlail\Forms\Interfaces\IInputField;
use SunnyFlail\Forms\Traits\ValidableFieldTrait;
use SunnyFlail\Forms\Traits\FieldTrait;

abstract class AbstractInputField implements IInputField
{

    use ContainerElementTrait, FieldTrait, ValidableFieldTrait;

    protected array $wrapperAttributes;
    protected array $errorAttributes;
    protected ?string $labelText;
    protected array $labelAttributes;

    public function __construct()
    {
        $this->valid = false;
        $this->error = null;
        $this->value = null;
    }

    public function resolve(array $values): bool
    {
        $value = $values[$this->getFullName()] ?? null;

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
        $inputId = $this->getInputId();
        $elements = [
            new LabelElement(
                for: $inputId,
                attributes: $this->labelAttributes,
                labelText: $this->labelText ?? $this->name
            ),
            $this->getInputElement()
        ];

        if ($this->error) {
            $elements[] = new ContainerElement(
                attributes: $this->errorAttributes,
                nestedElements: [new TextNodeElement($this->error)]
            );
        }
        
        $elements = [...$elements, ...$this->nestedElements];

        return new ContainerElement(
            attributes: $this->wrapperAttributes,
            nestedElements: $elements
        );
    }
     
    /**
     * Returns the input element / node containing input elements
     * 
     * @return IElement
     */
    abstract protected function getInputElement(): IElement;

}