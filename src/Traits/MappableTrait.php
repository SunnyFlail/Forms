<?php

namespace SunnyFlail\Forms\Traits;

use SunnyFlail\Forms\Interfaces\IField;
use SunnyFlail\Forms\Interfaces\IMappableField;

trait MappableTrait
{

    protected array $fields;

    public function withFields(IField ...$fields): IMappableField
    {
        foreach ($fields as $field) {
            $fieldName = $field->getName();
            $this->fields[$fieldName] = $field;
        }

        return $this;
    }

    public function withFieldValue(string $fieldName, $value): IMappableField
    {
        $this->field[$fieldName]->withValue($value);

        return $this;
    }

    public function hasField(string $fieldName): bool
    {
        return isset($this->fields[$fieldName]);
    }

    public function getFields(): array
    {
        return $this->fields;
    }

}