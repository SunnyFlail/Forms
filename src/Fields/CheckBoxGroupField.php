<?php

namespace SunnyFlail\Forms\Fields;

use SunnyFlail\HtmlAbstraction\Traits\ContainerElementTrait;
use SunnyFlail\HtmlAbstraction\Elements\CheckableElement;
use SunnyFlail\HtmlAbstraction\Elements\ContainerElement;
use SunnyFlail\HtmlAbstraction\Elements\TextNodeElement;
use SunnyFlail\HtmlAbstraction\Elements\LabelElement;
use SunnyFlail\HtmlAbstraction\Elements\NodeElement;
use SunnyFlail\Forms\Interfaces\ISelectableField;
use SunnyFlail\Forms\Interfaces\IInputField;
use SunnyFlail\Forms\Traits\SelectableTrait;
use SunnyFlail\Forms\Traits\FieldTrait;
use SunnyFlail\Forms\Traits\ValidableFieldTrait;

final class CheckBoxGroupField implements ISelectableField, IInputField
{
    use ContainerElementTrait, FieldTrait, SelectableTrait, ValidableFieldTrait;

    public function __construct(
        protected string $name,
        protected bool $useIntristicValues = true,
        protected array $options,
        protected array $inputAttributes = [],
        protected array $wrapperAttributes = [],
        protected array $labelAttributes = [],
        array $nestedElements = [],
        array $constraints = []
    ) {
        $this->valid = false;
        $this->error = null;
        $this->value = null;
        $this->nestedElements = $nestedElements;
        $this->constraints = $constraints;
    }

    public function resolve(array $values): bool
    {
        $value = $values[$this->name] ?? null;

        if (true !== ($error = $this->checkConstraints($value))) {
            $this->error = $error;
            return false;
        }

        if ($this->useIntristicValues && !in_array($value, $this->options)) {
            $this->error = $this->resolveErrorMessage('-1');
            return false;
        }

        $this->value = $value;

        return $this->valid = true;
    }

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

            $elements[] = new ContainerElement(
                attributes: $this->wrapperAttributes,
                nestedElements: [
                        new LabelElement(
                        for: $id,
                        labelText: $label,
                        attributes: $this->labelAttributes
                    ), new CheckableElement(
                        id: $id,
                        name: $name,
                        radio: true,
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

    private function resolveId(string $baseId, string $value)
    {
        $value = strtr($value, " ", "_");
        return $baseId . '--' . $value;
    }

}