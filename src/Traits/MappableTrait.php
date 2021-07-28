<?php

namespace SunnyFlail\Forms\Traits;

use SunnyFlail\Forms\Interfaces\IMappableContainer;
use SunnyFlail\Forms\Interfaces\IField;
use SunnyFlail\Forms\Interfaces\IFieldContainer;

/**
 * Trait for classes implementing IMappableContainer interface
 */
trait MappableTrait
{

    use ErrorTrait;
    
    /**
     * @var IField $fields
     */
    protected array $fields = [];

    /**
     * @var string|null $classFQCN Fully qualified class name of class this field will be mapped to
     *                  null defaults to primitve array
     */
    protected ?string $classFQCN = null;

    public function withFields(IField ...$fields): IMappableContainer
    {
        foreach ($fields as $field) {
            $fieldName = $field->getName();
            $this->fields[$fieldName] = $field;
        }

        return $this;
    }

    public function withFieldValue(string $fieldName, $value): IMappableContainer
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
        return $this->classFQCN;
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

    /**
     * Serializes Field Container (MappableField, RepeatedField or Form)
     * 
     * @param IFieldContainer $field
     * 
     * @return array
     */
    protected function serializeFieldContainer(IFieldContainer $field): array
    {
        $fields = [];

        foreach ($field->getFields() as $nestedField) {
            if ($nestedField instanceof IFieldContainer) {
                $fields += $this->serializeFieldContainer($nestedField);
                continue;
            }
            /**
             * @var IInputField $nestedField 
             */
            $fields += [$nestedField->getFullName() => $nestedField->jsonSerialize()];
        }

        return $fields;
    }

}