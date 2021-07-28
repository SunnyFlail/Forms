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
    protected array $wrapperAttributes;

    public function __toString(): string
    {
        $elements = $this->getContainerElement();

        $elements[] = $this->getErrorElement();

        return implode('', $elements);
    }

    public function jsonSerialize()
    {
        $baseId = $this->getInputId();
        $name = $this->getFullName();
        $options = [];
        foreach ($this->options as $label => $value) {
            $id = $this->resolveId($baseId, $label);
            $options[] = $this->serializeOption($id, $label, $value);
        }


        return $options;
    }

    protected function serializeOption($id, $label, $value): array
    {
        $attributes = $this->inputAttributes;
        $attributes['type'] = $this->radio ? 'radio' : 'checkbox';

        return [
            'fieldName' => static::class,
            'tagName' => 'INPUT',
            'id' => $id,
            'label' => $label,
            'valid' => $this->valid,
            'value' => $value,
            'error' => $this->error,
            'attributes' => $attributes
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
                attributes: $this->wrapperAttributes,
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