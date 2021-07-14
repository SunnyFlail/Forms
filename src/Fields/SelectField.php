<?php

namespace SunnyFlail\Forms\Fields;

use SunnyFlail\HtmlAbstraction\Elements\ContainerElement;
use SunnyFlail\HtmlAbstraction\Elements\TextNodeElement;
use SunnyFlail\HtmlAbstraction\Elements\OptionElement;
use SunnyFlail\HtmlAbstraction\Elements\SelectElement;
use SunnyFlail\HtmlAbstraction\Elements\LabelElement;
use SunnyFlail\HtmlAbstraction\Traits\AttributeTrait;
use SunnyFlail\Forms\Interfaces\ISelectableField;
use SunnyFlail\Forms\Interfaces\IInputField;
use SunnyFlail\Forms\Traits\ResolveSelectTrait;
use SunnyFlail\Forms\Traits\SelectableTrait;
use SunnyFlail\Forms\Traits\FieldTrait;

final class SelectField implements ISelectableField, IInputField
{

    use AttributeTrait, SelectableTrait, FieldTrait, ResolveSelectTrait;
    
    /**
     * @var string[]|string[][] $options 
     */

    public function __construct(
        protected string $name,
        protected bool $multiple = false,
        bool $required = false,
        protected array $inputAttributes = [],
        protected ?string $labelText = null,
        protected array $labelAttributes = [],
        protected array $optionAttributes = [],
        protected array $errorAttributes = [],
        array $options = [],
        array $errorMessages = [],
        array $constraints = [],
        bool $useIntristicValues = true
    ) {
        $this->error = null;
        $this->valid = false;
        $this->required = $required;
        $this->value = null;
        $this->errorMessages = $errorMessages;
        $this->constraints = $constraints;
        $this->options = $options;
        $this->useIntristicValues = $useIntristicValues;
    }

    public function __toString(): string
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

        $elements = [];
        $inputId = $this->getInputId();

        $elements[] = new LabelElement(
            for: $inputId,
            labelText: $this->labelText ?? $this->name,
            attributes: $this->labelAttributes
        );

        $elements[] = new SelectElement(
            id: $inputId,
            required: $this->required,
            multiple: $this->multiple,
            name: $this->getFullName(),
            attributes: $this->inputAttributes,
            options: $options
        );

        if (null !== $this->error) {
            $elements[] = new ContainerElement(
                attributes: $this->errorAttributes,
                nestedElements: [
                    new TextNodeElement($this->error)
                ]
            );
        }

        return new ContainerElement(
            attributes: $this->containerAttibutes,
            nestedElements: $elements
        );
    }

    private function createOption(string $label, string $value): OptionElement
    {
        if (is_numeric($label)) {
            $label = $value;
        }

        if ($this->multiple && is_array($this->value)) {
            $selected = in_array($value, $this->value);
        } else {
            $selected = ($value === $this->value);
        }

        return new OptionElement(
            value: $value,
            optionText: $label,
            attributes: $this->optionAttributes,
            selected: $selected
        );
    }

}