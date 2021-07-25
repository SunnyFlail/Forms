<?php

namespace SunnyFlail\Forms\Form;

use SunnyFlail\HtmlAbstraction\Elements\ButtonElement;
use SunnyFlail\HtmlAbstraction\Traits\AttributeTrait;
use SunnyFlail\Forms\Interfaces\IFormElement;
use SunnyFlail\Forms\Interfaces\IFileField;
use SunnyFlail\Forms\Traits\MappableTrait;
use Psr\Http\Message\ServerRequestInterface;
use SunnyFlail\Forms\Traits\WrapperFieldTrait;
use SunnyFlail\Forms\Traits\ErrorTrait;
use SunnyFlail\HtmlAbstraction\Interfaces\IElement;

/**
 * Abstraction over html forms with HTTP parameter resolving
 */
abstract class FormElement implements IFormElement
{

    use AttributeTrait, MappableTrait, WrapperFieldTrait, ErrorTrait;
    
    protected array $attributes = [];

    protected string $formMethod = 'GET';

    protected string $formName;

    protected string $buttonText = 'Submit';

    protected bool $useHtmlValidation = true;
    
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

    public function resolveForm(ServerRequestInterface $request): bool
    {
        $requestMethod = $request->getMethod();

        if (strcasecmp($this->formMethod, $requestMethod) !== 0) {
            return false;
        }

        if (strcasecmp($requestMethod, 'POST') === 0) {
            $params = $request->getParsedBody();
        } elseif (strcasecmp($requestMethod, 'GET') === 0) {
            $params = $request->getQueryParams();
        } else {
            return false;
        }

        if (is_array($params) && isset($params[$this->formName])) {
            $valid = true;
            $params = $params[$this->formName];
            
            $files = [];
            if ($this->withFiles) {
                $files = $request->getUploadedFiles()[$this->formName] ?? [];
            }

            foreach ($this->fields as $field) {
                if ($field instanceof IFileField) {
                    $field->resolve($files);
                    continue;
                }
                $field->resolve($params);

                if ($field->isValid() === false && $field->isRequired()) {
                    $valid = false;
                }
            }
        }

        return $this->valid = $valid ?? false;
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
        $attributes = $this->attributes;
        if (!$this->useHtmlValidation) {
            $attributes["novalidate"] = true;
        }
        if ($this->withFiles) {
            $attributes['enctype'] = 'multipart/form-data';
        }

        $attributes['id'] = $attributes['id'] ?? $this->formName;
        $attributes['method'] = $this->formMethod;

        $elements = [
            ...$this->topElements,
            ...array_values($this->fields),
            ...$this->middleElements,
            $this->getErrorElement(),
            $this->getSubmitButton(),
            ...$this->bottomElements  
        ];

        return '<form' .$this->getAttributeString($attributes) . '>'
                . implode('', $elements) . '</form>';
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
