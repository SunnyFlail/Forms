<?php

namespace SunnyFlail\Forms\Fields;

use SunnyFlail\Forms\Traits\ValidableFieldTrait;
use SunnyFlail\HtmlAbstraction\Elements\InputElement;
use SunnyFlail\HtmlAbstraction\Elements\TextNodeElement;
use SunnyFlail\HtmlAbstraction\Interfaces\IElement;

final class JsonField extends AbstractInputField
{

    use ValidableFieldTrait;

    public function __construct(
        string $name,
        bool $required = true,
        array $constraints = [],
        array $errorMessages = [],
        string $labelText = '',
        array $labelAttributes = [],
        protected array $inputAttributes = [],
        array $containerAttributes = [],
        array $errorAttributes = [],
    ) {
        $this->name = $name;
        $this->required = $required;
        $this->labelText = $labelText;
        $this->constraints = $constraints;
        $errorMessages['-2'] = $errorMessages['-2'] ?? "Provided value wasn't proper json code!";
        $this->errorMessages = $errorMessages;
        $this->errorAttributes = $errorAttributes;
        $this->labelAttributes = $labelAttributes;
        $this->containerAttributes = $containerAttributes;
    }

    public function getInputElement(): IElement
    {
        $value = $this->rememberValue ? $this->value : null;

        return new InputElement(
            id: $this->getInputId(),
            type: 'hidden',
            name: $this->getFullName(),
            attributes: $this->inputAttributes,
            value: $value
        );
    }

    public function resolve(array $values): bool
    {
        if (!isset($values[$this->name])) {
            if ($this->required) {
                $this->error = $this->resolveErrorMessage('-1');
                return $this->valid = false;
            }
            $this->value = [];
            return $this->valid = true;
        }
        try {
            $value = json_decode(
                json: $values[$this->name],
                associative: true,
                flags: JSON_THROW_ON_ERROR
            );
        } catch (\Throwable) {
            $this->error = $this->resolveErrorMessage('-2');
            return $this->valid = false;
        }

        if ($this->checkConstraints($value)) {
            $this->value = $value;
        }

        return $this->valid;
    }

    public function getLabelElement(): IElement|array
    {
        return new TextNodeElement('');
    }

    public function jsonSerialize()
    {
        $attributes = $this->inputAttributes;
        $attributes['type'] = 'hidden';

        return [
            [
                'fieldName' => static::class,
                'tagName' => 'INPUT',
                'name' => $this->getFullName(),
                'id' => $this->getInputId(),
                'required' => $this->required,
                'valid' => $this->valid,
                'label' => $this->labelText ?? $this->name,
                'value' => $this->value,
                'error' => $this->error,
                'attributes' => $attributes
            ]
        ];
    }

}