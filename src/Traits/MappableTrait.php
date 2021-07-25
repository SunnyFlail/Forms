<?php

namespace SunnyFlail\Forms\Traits;

use SunnyFlail\Forms\Interfaces\IMappableField;
use SunnyFlail\Forms\Interfaces\IField;

/**
 * Trait for classes implementing IMappableField interface
 */
trait MappableTrait
{

    use ErrorTrait;
    
    /**
     * @var IField $fields
     */
    protected array $fields = [];

    /**
     * @var string|null $className Fully qualified class name of class this field will be mapped to
     *                  null defaults to primitve array
     */
    protected ?string $className = null;

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

    abstract public function getName(): string;

    public function getFields(): array
    {
        return $this->fields;
    }

    public function getClassName(): ?string
    {
        return $this->className;
    }

    public function getError(): mixed
    {
        $errors = [];
        foreach ($this->fields as $name => $field) {
            $errors[$name] = $field->getError();
        }
        $errors[$this->getName()] = $this->error;
        
        return $errors;
    }

}