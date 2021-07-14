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

    /**
     * @param string        $name          Name of the field
     * @param bool          $required
     * @param array         $attributes    Attributes to be pa
     * @param string[]      $errorMessages Array containing error messages
     *                                     Indexes MUST be numeric strings
     *                                     Index "-1" Is for message shown
     *                                     if no value was provided for a
     *                                     required field 
     * @param IConstraint[] $constraints
     */
    public function __construct(
        protected string $name,
        bool $required = true,
        protected array $wrapperAttributes = [],
        protected array $errorAttributes = [],
        protected ?string $labelText = null,
        protected array $labelAttributes = [],
        array $errorMessages = [],
        array $nestedElements = [],
        array $constraints = []
    ) {
        $this->error = null;
        $this->value = null;
        $this->valid = false;
        $this->required = $required;
        $this->errorMessages = $errorMessages;
        $this->nestedElements = $nestedElements;
        $this->constraints = $constraints;
    }

    public function resolve(array $values): bool
    {
        $value = $values[$this->getFullName()] ?? null;

        if (true !== ($error = $this->checkConstraints($value))) {
            $this->error = $error;
            return false;
        }

        $this->withValue($value);
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