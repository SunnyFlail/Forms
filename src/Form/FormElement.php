<?php

namespace SunnyFlail\Forms\Form;

use SunnyFlail\HtmlAbstraction\Elements\ButtonElement;
use SunnyFlail\HtmlAbstraction\Traits\AttributeTrait;
use SunnyFlail\Forms\Interfaces\IFormElement;
use SunnyFlail\Forms\Interfaces\IFileField;
use SunnyFlail\Forms\Traits\MappableTrait;
use Psr\Http\Message\UploadedFileInterface;
use SunnyFlail\Forms\Traits\WrapperFieldTrait;
use SunnyFlail\Forms\Traits\ErrorTrait;
use SunnyFlail\HtmlAbstraction\Interfaces\IElement;

/**
 * Abstraction over html forms with HTTP parameter resolving
 */
abstract class FormElement implements IFormElement
{

    use AttributeTrait, MappableTrait, WrapperFieldTrait, ErrorTrait;
    
    /**
     * @var bool $valid Whether form's field values are valid
     */
    protected ?bool $valid = null;

    /**
     * @var string $attributes Attributes for this form's html tag
     */
    protected array $attributes = [];

    /**
     * @var string $formMethod HTTP method name this form will respond to
     */
    protected string $formMethod = 'GET';

    /**
     * @var string $formName Name of the form 
     */
    protected string $formName;

    /**
     * @var string|null $formAction Action attribute of form 
     */
    protected ?string $formAction = null;

    /**
     * @var string $buttonText Text to be displayed inside submit button
     */
    protected string $buttonText = 'Submit';

    /**
     * @var bool $useHtmlValidation Should this form use client-side browser form validation
     */
    protected bool $useHtmlValidation = true;

    /**
     * @var bool $renderButton Should submit button be rendered
     */
    protected bool $renderButton = true;

    /**
     * @var bool $withFiles If set to true sets the enctype (encoding type) to multipart/form-data
     */
    protected bool $withFiles = false;

    protected array $buttonAttributes = [];
    protected array $buttonElements = [];

    public function getName(): string
    {
        return $this->formName;
    }

    public function getFormMethod(): string
    {
        return $this->formMethod;
    }

    public function resolveForm(array $requestParams, UploadedFileInterface|array $uploadedFiles): bool
    {
        $valid = true;

        foreach ($this->fields as $field) {
            if ($field instanceof IFileField) {
                $field->resolve($uploadedFiles);
                continue;
            }
            $field->resolve($requestParams);

            if (!$field->isValid() && $field->isRequired()) {
                $valid = false;
            }
        }

        return ($this->valid = $valid);
    }

    public function addError(string $error): IFormElement
    {
        $this->error = $error;
        $this->valid = false;
        
        return $this;
    }

    /**
     * Returns a html string representation of form
     * 
     * @return string
     */
    public function __toString(): string
    {
        $button = '';

        if ($this->renderButton) {
            $button = $this->getSubmitButton();
        }

        $elements = implode('', [
            ...$this->topElements,
            ...array_values($this->fields),
            ...$this->middleElements,
            $this->getErrorElement(),
            $button,
            ...$this->bottomElements  
        ]);

        return '<form' . $this->getHTMLAttributes() . '>' .  $elements . '</form>';
    }
    
    /**
     * Returns an array representing this form
     * 
     * @return array
     */
    public function jsonSerialize()
    {
        $fields = $this->serializeFieldContainer($this);
        $attributes = $this->getAttributes();
        $id = array_assoc_shift('id', $attributes);
        $method = array_assoc_shift('method', $attributes);

        return [
            'tagName' => 'FORM',
            'id' => $id,
            'valid' => $this->valid,
            'method' => $method,
            'attributes' => $attributes,
            'action' => $this->formAction,
            'fields' => $fields,
            'error' => $this->error
        ];
    }

    public function getAttributes(): array
    {
        $attributes = $this->attributes;
        if (!$this->useHtmlValidation) {
            $attributes["novalidate"] = true;
        }
        if ($this->withFiles) {
            $attributes['enctype'] = 'multipart/form-data';
        }

        $attributes['id'] = $attributes['id'] ?? 'form__' . $this->formName;
        $attributes['method'] = $this->formMethod;

        return $attributes;
    }

    public function getHTMLAttributes(): string
    {
        return $this->getAttributeString(
            $this->getAttributes()
        );
    }

    public function getSubmitButton(): IElement
    {
        return new ButtonElement(
            type: "submit",
            attributes: $this->buttonAttributes,
            text: $this->buttonText,
            nestedElements: $this->buttonElements
        );
    }

}
