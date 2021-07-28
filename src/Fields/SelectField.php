<?php

namespace SunnyFlail\Forms\Fields;

use SunnyFlail\Forms\Interfaces\IWrapperField;
use SunnyFlail\HtmlAbstraction\Elements\ContainerElement;
use SunnyFlail\HtmlAbstraction\Elements\OptionElement;
use SunnyFlail\HtmlAbstraction\Elements\SelectElement;
use SunnyFlail\Forms\Interfaces\ISelectableField;
use SunnyFlail\Forms\Interfaces\IInputField;
use SunnyFlail\Forms\Traits\MultipleValueSelectableTrait;
use SunnyFlail\Forms\Traits\SingleInputFieldTrait;
use SunnyFlail\HtmlAbstraction\Interfaces\IElement;

final class SelectField implements ISelectableField, IInputField, IWrapperField
{

    use MultipleValueSelectableTrait, SingleInputFieldTrait;
    
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
        $this->valid = null;
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

    public function jsonSerialize()
    {
        $options = $this->serializeOptions($this->options);

        return[
            [
                'fieldName' => static::class,
                'tagName' => 'SELECT',
                'name' => $this->getFullName(),
                'label' => $this->labelText,
                'valid' => $this->valid,
                'id' => $this->getInputId(),
                'required' => $this->required,
                'multiple' => $this->multiple,
                'error' => $this->error,
                'options' => $options,
                'attributes' => $this->inputAttributes
            ]
        ];
    }

    /**
     * Serializes provided options
     * 
     * @param array $options Array confirming to options schema
     * 
     * @return array
     */
    private function serializeOptions(array $options): array
    {
        $options = [];
        
        foreach ($this->options as $label => $value) {
            /** Check if this is a group */
            if (is_array($value)) {
                $options[] = $this->serializeOptionGroup($label, $value);

                continue;
            }

            $options[] = $this->serializeOption($label, $value);
        }

        return $options;
    }

    /**
     * Serializes option group
     * 
     * @param string $groupName Name to be displayed
     * @param string[] $options Options of this group
     * 
     * @return array
     */
    private function serializeOptionGroup(string $groupName, array $options): array
    {
        $options = $this->serializeOptions($options);

        return [
            'tagName' => 'OPTGROUP',
            'attributes' => [
                'label' => $groupName
            ],
            'options' => $options
        ];
    }
    
    /**
     * Serializes option
     * 
     * @param string $label If numeric will be replaced with value
     * @param string $value Value of option
     * 
     * @return array
     */
    private function serializeOption(string $label, string $value): array
    {
        $label = is_numeric($label) ? $value : $label;

        return [
            'tagName' => 'OPTION',
            'label' => $label,
            'value' => $value,
            'selected' => $this->isSelected($value),
            'attributes' => $this->optionAttributes
        ];
    }

    public function getInputElement(): IElement|array
    {
        $options = [];
        
        foreach ($this->options as $label => $value) {
            /** Check if this is a group */
            if (is_array($value)) {
                $options[] = $this->createOptionGroup($label, $value);

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

    /**
     * Creates a ContainerElement with provided label
     * 
     * @param string $groupName Label to be displayed as name of group
     * @param array $option Associative array
     * 
     * @return ContainerElement
     */
    private function createOptionGroup(string $groupName, array $options): ContainerElement
    {
        $nestedOptions = [];

        foreach ($options as $label => $option) {
            $nestedOptions[] = $this->createOption($label, $option);
        }
        
        return new ContainerElement(
            tag: 'optgroup',
            attributes: ['label' => $groupName],
            nestedElements: $nestedOptions
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

        $selected = $this->isSelected($value);

        return new OptionElement(
            value: $value,
            optionText: $label,
            attributes: $this->optionAttributes,
            selected: $selected
        );
    }

    /**
     * Checks whether this option was selected
     * 
     * @param string $value
     * 
     * @return bool
     */
    private function isSelected(string $value): bool
    {
        if ($this->rememberValue) {
            
            if ($this->multiple && is_array($this->value)) {

               return in_array($value, $this->value);
            }

            return ($value === $this->value);
        } 

        return false;
    }

}