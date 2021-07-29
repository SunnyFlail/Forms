<?php

namespace SunnyFlail\Forms\Traits;

use SunnyFlail\Forms\Interfaces\IField;
use SunnyFlail\Forms\Interfaces\IFormElement;

/**
 * Trait for Elements implementing IField interface
 */
trait FieldTrait
{
    use ErrorTrait;

    /**
     * @var IFormElement Reference to parent form
     */
    protected IFormElement $form;

    /**
     * @var bool|null $valid
     */
    protected ?bool $valid;

    /**
     * @var bool $required Bool indicating whether this field needs to be valid
     */
    protected bool $required;

    public function isValid(): bool
    {
        return $this->valid;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function withError(string $error): IField
    {
        $this->error = $error;
        $this->valid = false;
        return $this;
    }

    public function withForm(IFormElement $form): IField
    {
        $this->form = $form;
        return $this;
    }

}