<?php

namespace SunnyFlail\Forms\Fields;

use SunnyFlail\Forms\Interfaces\IWrapperField;
use SunnyFlail\HtmlAbstraction\Elements\ContainerElement;
use SunnyFlail\HtmlAbstraction\Elements\OptionElement;
use SunnyFlail\HtmlAbstraction\Elements\SelectElement;
use SunnyFlail\HtmlAbstraction\Elements\LabelElement;
use SunnyFlail\HtmlAbstraction\Traits\AttributeTrait;
use SunnyFlail\Forms\Interfaces\ISelectableField;
use SunnyFlail\Forms\Interfaces\IInputField;
use SunnyFlail\Forms\Traits\WrapperFieldTrait;
use SunnyFlail\Forms\Traits\MultipleValueFieldTrait;
use SunnyFlail\Forms\Traits\SelectableTrait;
use SunnyFlail\HtmlAbstraction\Interfaces\IElement;

final class SelectField implements ISelectableField, IInputField, IWrapperField
{

    use AttributeTrait, SelectableTrait, MultipleValueFieldTrait, WrapperFieldTrait;
    
    /**
     * @param string[]|string[][] $options
     *  
     */
    public function __construct(
        protected string $name,
        array $options = [],
        bool $required = false,
        protected bool $rememberValue = true,
        bool $multiple = false,
        bool $useIntristicValues = true,
        array $constraints = [],
        array $errorMessages = [],
        array $topElements = [],
        array $middleElements = [],
        array $bottomElements = [],
        protected array $inputAttributes = [],
        protected array $wrapperAttributes = [],
        ?string $labelText = null,
        array $labelAttributes = [],
        protected array $optionAttributes = [],
        array $errorAttributes = []
    ) {
        $this->error = null;
        $this->valid = false;
        $this->value = null;
        $this->options = $options;
        $this->required = $required;
        $this->multiple = $multiple;
        $this->constraints = $constraints;
        $this->topElements = $topElements;
        $this->middleElements = $middleElements;
        $this->bottomElements = $bottomElements;
        $this->errorMessages = $errorMessages;
        $this->labelText = $labelText;
        $this->labelAttributes = $labelAttributes;
        $this->errorAttributes = $errorAttributes;
        $this->useIntristicValues = $useIntristicValues;
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
        $options = [];
        
        foreach ($this->options as $label => $value) {
            /** Check if this is a group */
            if (is_array($value)) {
                $options[] = new ContainerElement(
                    tag: 'optgroup',
                    attributes: ['label' => $label],
                    nestedElements: array_map(
                        [$this, "createOption"],
                        array_keys($value),
                        $value
                    )
                );
                continue;
            }

            $options[] = $this->createOption($label, $value);
        }

        return new SelectElement(
            id: $this->getInputId(),
            required: $this->required,
            multiple: $this->multiple,
            name: $this->getFullName(),
            attributes: $this->inputAttributes,
            options: $options
        );
    }

    public function getLabelElement(): IElement|array
    {
        return new LabelElement(
            for: $this->getInputId(),
            labelText: $this->labelText ?? $this->name,
            attributes: $this->labelAttributes,
        );
    }

    /**
     * Creates an OptionElement with Provided Label and Value
     * 
     * @param string $label
     * @param string $value
     * 
     * @return OptionElement
     */
    private function createOption(string $label, string $value): OptionElement
    {
        if (is_numeric($label)) {
            $label = $value;
        }

        if ($this->rememberValue) {
            if ($this->multiple && is_array($this->value)) {
                $selected = in_array($value, $this->value);
            } else {
                $selected = ($value === $this->value);
            }
        } else {
            $selected = false;
        }

        return new OptionElement(
            value: $value,
            optionText: $label,
            attributes: $this->optionAttributes,
            selected: $selected
        );
    }

}