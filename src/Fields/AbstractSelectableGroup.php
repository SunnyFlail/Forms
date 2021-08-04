<?php

namespace SunnyFlail\Forms\Fields;

use SunnyFlail\HtmlAbstraction\Elements\ContainerElement;
use SunnyFlail\HtmlAbstraction\Elements\CheckableElement;
use SunnyFlail\HtmlAbstraction\Elements\LabelElement;
use SunnyFlail\Forms\Interfaces\ISelectableField;
use SunnyFlail\Forms\Interfaces\IInputField;
use SunnyFlail\Forms\Traits\MultipleElementFieldTrait;
use SunnyFlail\Forms\Traits\ValidableFieldTrait;
use SunnyFlail\Forms\Traits\SelectableTrait;
use SunnyFlail\HtmlAbstraction\Interfaces\IElement;

abstract class AbstractSelectableGroup implements ISelectableField, IInputField
{
    use SelectableTrait, ValidableFieldTrait, MultipleElementFieldTrait;

    protected bool $radio;
    protected array $containerAttributes;

    public function __toString(): string
    {
        $elements = $this->getContainerElement();

        $elements[] = $this->getErrorElement();

        return implode('', $elements);
    }

    public function jsonSerialize()
    {
        $baseId = $this->getInputId();

        $attributes = $this->inputAttributes;
        $attributes['type'] = $this->radio ? 'radio' : 'checkbox';

        $options = [];

        foreach ($this->options as $label => $value) {
            $id = $this->resolveId($baseId, $label);
            $options[$label] = $this->serializeOption($id, $label, $value);
        }

        return [
            'tagName' => 'INPUT',
            'name' => $this->getFullName(),
            'label' => $label,
            'valid' => $this->valid,
            'value' => $this->value,
            'options' => $options,
            'error' => $this->error,
            'attributes' => $attributes
        ];
    }

    /**
     * Returns a JSON representation of an option
     * 
     * @param string $id Html ID attribute of input
     * @param string $label Label text to be displayed
     * @param string $value Value of this option
     * 
     * @return array 
     */
    protected function serializeOption(string $id, string $label, string $value): array
    {
        return [
            'label' => $label,
            'id' => $id,
            'value' => $value,
            'checked' => $this->isChecked($value)
        ];
    }

    public function getContainerElement(): IElement|array
    {
        $name = $this->getFullName();
        $baseId = $this->getInputId();
        $elements = [];

        foreach ($this->options as $label => $value) {
            $id = $this->resolveId($baseId, $value);
            $label = $this->createLabelElement($id, $label, $value);
            $input = $this->createInputElement($id, $name, $value); 

            $elements[] = new ContainerElement(
                attributes: $this->containerAttributes,
                nestedElements: [
                    $label,
                    $input
                ]
            );
        }

        return $elements;
    }

    public function getLabelElement(): IElement|array
    {
        $baseId = $this->getInputId();
        $labels = [];
        
        foreach ($this->options as $label => $value) {
            $id = $this->resolveId($baseId, $value);

            $labels[] = $this->createLabelElement($id, $label, $value);
        }

        return $labels;
    }

    public function getInputElement(): IElement|array
    {
        $name = $this->getFullName();
        $baseId = $this->getInputId();
        $inputs = [];

        foreach ($this->options as $value) {
            $id = $this->resolveId($baseId, $value);

            $inputs[] = $this->createInputElement($id, $name, $value);
        }

        return $inputs;
    }

    /**
     * Creates a label element
     * 
     * @param string $id
     * @param string $label
     * @param string $value
     * 
     * @return LabelElement 
     */
    protected function createLabelElement(string $id, string $label, string $value): LabelElement
    {
        if (is_numeric($label)) {
            $label = $value;
        }

        return new LabelElement(
            for: $id,
            labelText: $label,
            attributes: $this->labelAttributes
        );
    }

    /**
     * Creates an input element
     * 
     * @param string $id
     * @param string $name
     * @param string $value
     * 
     * @return CheckableElement 
     */
    protected function createInputElement(string $id, string $name, string $value): CheckableElement
    {
        return new CheckableElement(
            id: $id,
            name: $name,
            value: $value,
            radio: $this->radio,
            checked: $this->isChecked($value),
            attributes: $this->inputAttributes
        );
    }

    /**
     * Checks whether this option should be checked
     * 
     * @param string $value
     * 
     * @return bool 
     */
    abstract protected function isChecked(string $value): bool;

}