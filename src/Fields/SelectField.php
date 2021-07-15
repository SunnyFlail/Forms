<?php

namespace SunnyFlail\Forms\Fields;

use SunnyFlail\HtmlAbstraction\Interfaces\IContainerElement;
use SunnyFlail\HtmlAbstraction\Elements\ContainerElement;
use SunnyFlail\HtmlAbstraction\Elements\TextNodeElement;
use SunnyFlail\HtmlAbstraction\Elements\OptionElement;
use SunnyFlail\HtmlAbstraction\Elements\SelectElement;
use SunnyFlail\HtmlAbstraction\Elements\LabelElement;
use SunnyFlail\HtmlAbstraction\Traits\ContainerElementTrait;
use SunnyFlail\HtmlAbstraction\Traits\AttributeTrait;
use SunnyFlail\Forms\Interfaces\ISelectableField;
use SunnyFlail\Forms\Interfaces\IInputField;
use SunnyFlail\Forms\Traits\ResolveSelectTrait;
use SunnyFlail\Forms\Traits\SelectableTrait;
use SunnyFlail\Forms\Traits\FieldTrait;

final class SelectField implements ISelectableField, IInputField, IContainerElement
{

    use ContainerElementTrait, AttributeTrait, SelectableTrait, FieldTrait, ResolveSelectTrait;
    
    /**
     * @var string[]|string[][] $options 
     */

    public function __construct(
        protected string $name,
        array $options = [],
        bool $required = false,
        protected bool $rememberValue = true,
        protected bool $multiple = false,
        bool $useIntristicValues = true,
        array $constraints = [],
        array $errorMessages = [],
        array $nestedElements = [],
        protected array $inputAttributes = [],
        protected array $wrapperAttributes = [],
        protected ?string $labelText = null,
        protected array $labelAttributes = [],
        protected array $optionAttributes = [],
        protected array $errorAttributes = []
    ) {
        $this->error = null;
        $this->valid = false;
        $this->value = null;
        $this->required = $required;
        $this->errorMessages = $errorMessages;
        $this->nestedElements = $nestedElements;
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

        array_push($elements, ...$this->nestedElements); 

        return new ContainerElement(
            attributes: $this->wrapperAttributes,
            nestedElements: $elements
        );
    }

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