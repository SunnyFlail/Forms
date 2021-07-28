<?php

namespace SunnyFlail\Forms\Traits;

/**
 * Trait for fields whose inputs have id
 */
trait IdentifableFieldTrait
{

    use FieldTrait;

    /**
     * @var string $name Name of the input element
     */
    protected string $name;
    
    public function getName(): string
    {
        return $this->name;
    }

    public function getInputId(): string
    {
        return $this->form->getName() . "-"  . $this->name;
    }

}