<?php

namespace SunnyFlail\Forms\Traits;

use SunnyFlail\Forms\Interfaces\IField;
use SunnyFlail\Forms\Interfaces\IFormElement;
use SunnyFlail\HtmlAbstraction\Elements\ContainerElement;
use SunnyFlail\HtmlAbstraction\Elements\TextNodeElement;
use SunnyFlail\HtmlAbstraction\Interfaces\IElement;

/**
 * Trait for Elements implementing IField interface
 */
trait FieldTrait
{
    /**
     * @var IFormElement Reference to parent form
     */
    protected IFormElement $form;

    /**
     * @var bool $valid
     */
    protected bool $valid;

    /**
     * @var string|null $error Message that is shown if this field is invalid
     */
    protected ?string $error;

    /**
     * @var array $errorAttributes Attributes to add to error Element
     */
    protected array $errorAttributes;

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
        return $this;
    }

    public function withForm(IFormElement $form): IField
    {
        $this->form = $form;
        return $this;
    }

    public function getErrorElement(): ?IElement
    {
        if (null !== $this->error) {
            return new ContainerElement(
                attributes: $this->errorAttributes,
                nestedElements: [
                    new TextNodeElement($this->error)
                ]
            );
        }

        return null;
    }

}