<?php

namespace SunnyFlail\Forms\Fields;

use SunnyFlail\Forms\Elements\ContainerElement;
use SunnyFlail\Forms\Elements\LabelElement;
use SunnyFlail\Forms\Elements\OptionElement;
use SunnyFlail\Forms\Elements\SelectElement;
use SunnyFlail\Forms\Elements\TextNodeElement;
use SunnyFlail\Forms\Interfaces\IInputField;
use SunnyFlail\Forms\Interfaces\ISelectableField;
use SunnyFlail\Forms\Traits\AttributeTrait;
use SunnyFlail\Forms\Traits\FieldTrait;
use SunnyFlail\Forms\Traits\InputFieldTrait;
use SunnyFlail\Forms\Traits\SelectableTrait;

final class SelectField implements ISelectableField, IInputField
{

    use AttributeTrait, SelectableTrait, FieldTrait, InputFieldTrait;
    
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
        protected array $options = [],
        array $errorMessages = []
    ) {
        $this->error = null;
        $this->valid = false;
        $this->required = $required;
        $this->value = null;
        $this->errorMessages = $errorMessages;
    }

    public function resolve(array $values): bool
    {
        $value = $values[$this->getName()] ?? null;

        if ($this->required && null === $value) {
            $this->error = $this->resolveErrorMessage("-1");

            return false;
        }

        if ($this->multiple && is_array($value)) {
            $value = array_intersect($value, $this->option);

            if (!$value) {
                $this->error = $this->resolveErrorMessage("0");
            }

            return false;
        }

        if (!in_array($value, $this->options)) {
            $this->error = $this->resolveErrorMessage("0");

            return false;
        }

        $this->value = $value;

        return $this->valid = true;
    }

    public function __toString(): string
    {
        $options = [];
        
        foreach ($this->options as $label => $value) {
            /**
 * Check if this is a group 
*/
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