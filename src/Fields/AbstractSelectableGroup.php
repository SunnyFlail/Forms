<?php

namespace SunnyFlail\Forms\Fields;

use SunnyFlail\HtmlAbstraction\Elements\ContainerElement;
use SunnyFlail\HtmlAbstraction\Elements\CheckableElement;
use SunnyFlail\HtmlAbstraction\Elements\LabelElement;
use SunnyFlail\HtmlAbstraction\Elements\NodeElement;
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
        $elements[] = $this->getErrorElement();

        return new NodeElement($elements);
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

    abstract protected function isChecked(string $value): bool;

}