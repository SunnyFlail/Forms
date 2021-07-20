<?php

namespace SunnyFlail\Forms\Form;

use SunnyFlail\HtmlAbstraction\Elements\ButtonElement;
use SunnyFlail\HtmlAbstraction\Traits\AttributeTrait;
use SunnyFlail\Forms\Interfaces\IFormElement;
use SunnyFlail\Forms\Interfaces\IFileField;
use SunnyFlail\Forms\Traits\MappableTrait;
use Psr\Http\Message\ServerRequestInterface;
use SunnyFlail\Forms\Traits\ContainerFieldTrait;
use SunnyFlail\HtmlAbstraction\Elements\ContainerElement;
use SunnyFlail\HtmlAbstraction\Elements\TextNodeElement;
use SunnyFlail\HtmlAbstraction\Interfaces\IElement;

/**
 * Abstraction over html forms with HTTP parameter resolving
 */
abstract class FormElement implements IFormElement
{

    use AttributeTrait, MappableTrait, ContainerFieldTrait;
    
    protected array $attributes = [];

    protected string $formMethod = 'GET';

    protected string $formName = "";

    protected string $buttonText = 'Submit';
    
    protected ?string $error = null;

    protected bool $useHtmlValidation = true;

    protected array $errorAttributes = [];

    protected array $buttonAttributes = [];
    protected array $buttonElements = [];

    protected array $topElements = [];
    protected array $middleElements = [];
    protected array $bottomElements = [];

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
            
            foreach ($this->fields as $field) {
                if ($field instanceof IFileField) {
                    $field->resolve($request->getUploadedFiles());
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

    private function getErrorElement(): ?IElement
    {
        if ($this->error) {
            return new ContainerElement(
                attributes: $this->errorAttributes,
                nestedElements: [new TextNodeElement($this->error)]
            );
        }
        return null;
    }

    private function getSubmitButton(): IElement
    {
        return new ButtonElement(
            type: "submit",
            attributes: $this->buttonAttributes,
            text: $this->buttonText,
            nestedElements: $this->buttonElements
        );
    }

}
