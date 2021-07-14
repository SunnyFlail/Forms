<?php

namespace SunnyFlail\Forms\Fields;

use SunnyFlail\HtmlAbstraction\Traits\ContainerElementTrait;
use SunnyFlail\HtmlAbstraction\Elements\ContainerElement;
use SunnyFlail\HtmlAbstraction\Elements\CheckableElement;
use SunnyFlail\HtmlAbstraction\Elements\TextNodeElement;
use SunnyFlail\HtmlAbstraction\Elements\LabelElement;
use SunnyFlail\HtmlAbstraction\Elements\NodeElement;
use SunnyFlail\Forms\Interfaces\ISelectableField;
use SunnyFlail\Forms\Interfaces\IInputField;
use SunnyFlail\Forms\Traits\ValidableFieldTrait;
use SunnyFlail\Forms\Traits\SelectableTrait;
use SunnyFlail\Forms\Traits\FieldTrait;

abstract class AbstractSelectableGroup implements ISelectableField, IInputField
{
    use ContainerElementTrait, FieldTrait, SelectableTrait, ValidableFieldTrait;

    protected bool $radio;

    public function __toString(): string
    {
        $elements = [];
        $name = $this->getFullName();
        $baseId = $this->getInputId();

        foreach ($this->options as $label => $value) {
            if (is_numeric($label)) {
                $label = $value;
            }

            $id = $this->resolveId($baseId, $value);
            $checked = ($this->value === $value);
            $radio = $this->radio;

            $elements[] = new ContainerElement(
                attributes: $this->wrapperAttributes,
                nestedElements: [
                        new LabelElement(
                        for: $id,
                        labelText: $label,
                        attributes: $this->labelAttributes
                    ),
                    new CheckableElement(
                        id: $id,
                        name: $name,
                        radio: $radio,
                        checked: $checked,
                        attributes: $this->inputAttributes
                    )
                ]
            );
        }

        if (null !== $this->error) {
            $elements[] = new ContainerElement(
                attributes: $this->errorAttributes,
                nestedElements: [
                    new TextNodeElement($this->error)
                ]
            );
        }

        return new NodeElement($elements);
    }

    protected function resolveId(string $baseId, string $value)
    {
        $value = strtr($value, " ", "_");
        return $baseId . '--' . $value;
    }

}