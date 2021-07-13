<?php

namespace SunnyFlail\Forms\Form;

use SunnyFlail\HtmlAbstraction\Elements\ButtonElement;
use SunnyFlail\HtmlAbstraction\Traits\AttributeTrait;
use SunnyFlail\Forms\Interfaces\IFormElement;
use SunnyFlail\Forms\Interfaces\IFileField;
use SunnyFlail\Forms\Traits\MappableTrait;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Abstraction over html forms with HTTP parameter resolving
 */
abstract class FormElement implements IFormElement
{

    use AttributeTrait, MappableTrait;
    
    protected array $attributes = [];

    protected string $formMethod = "GET";

    protected string $formName = "";

    protected string $buttonText = "Submit";

    protected bool $useHtmlValidation = true;

    protected array $buttonAttributes = [];
    protected array $buttonElements = [];

    public function getName(): string
    {
        return $this->formName;
    }

    public function resolveForm(ServerRequestInterface $request): bool
    {
        $requestMethod = $request->getMethod();

        if (($this->formMethod === "POST"
            && $requestMethod === "POST"
            && $params = $request->getParsedBody())
            || ($this->formMethod === "GET"
            && $requestMethod === "GET"
            && $params = $request->getQueryParams())
        ) {
            if (is_array($params) && isset($params[$this->formName])) {
                $valid = true;

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
        }

        return $valid ?? false;
    }

    /**
     * Returns a html string representation of form
     * 
     * @return string
     */
    public function __toString(): string
    {
        $attributes = $this->attributes;
        $attributes["novalidate"] = $this->useHtmlValidation;
        $attributes['id'] = $attributes['id'] ?? $this->formName;
        $attributes['method'] = $this->formMethod;

        $elements = $this->fields;
        $elements[] = new ButtonElement(
            type: "submit",
            attributes: $this->buttonAttributes,
            text: $this->buttonText,
            nestedElements: $this->buttonElements
        );

        return '<form' .$this->getAttributeString($attributes) . '>'
                . implode('', $elements) . '</form>';
    }

}
