<?php

namespace SunnyFlail\Forms\Form;

use SunnyFlail\Forms\Exceptions\FormNotFoundException;
use SunnyFlail\Forms\Exceptions\FormBuilderException;
use SunnyFlail\HtmlAbstraction\Interfaces\IElement;
use SunnyFlail\Forms\Interfaces\IProviderFactory;
use SunnyFlail\Forms\Interfaces\IFormElement;
use SunnyFlail\Forms\Interfaces\IValueMapper;
use SunnyFlail\Forms\Interfaces\IFormBuilder;
use SunnyFlail\Forms\Interfaces\IField;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionClass;

final class FormBuilder implements IFormBuilder
{

    private ?IFormElement $form = null;

    public function __construct(
        private IValueMapper $mapper,
        private IProviderFactory $providerFactory,
    ) {}

    public function __toString()
    {
        return $this->form->__toString();
    }

    public function add(IField $field): IFormBuilder
    {
        $this->checkFormValidity();

        $field = $field->withForm($this->form);
        $this->form->withFields($field);

        return $this;
    }

    public function addError(string $error): IFormBuilder
    {
        $this->checkFormValidity();

        $this->valid = false;
        $this->form->addError($error);

        return $this;
    }

    public function addElementAtStart(IElement $element): IFormBuilder
    {
        $this->checkFormValidity();

        $this->form->addElementAtStart($element);

        return $this;
    }

    public function addElementInMiddle(IElement $element): IFormBuilder
    {
        $this->checkFormValidity();

        $this->form->addElementInMiddle($element);
        
        return $this;
    }

    public function addElementAtEnd(IElement $element): IFormBuilder
    {
        $this->checkFormValidity();

        $this->form->addElementAtEnd($element);
        
        return $this;
    }

    public function getProcessedData(): object|array
    {
        $this->checkFormValidity();

        return $this->mapper->scrapeValues($this->form);
    }

    public function processForm(ServerRequestInterface $request): bool
    {
        $this->checkFormValidity();

        if (($params = $this->getFormParameters($request)) === null) {
            return false;
        }
        
        $formName = $this->form->getName();

        if (!isset($params[$formName])) {
            return false;
        }
        
        $params = $params[$this->formName];
        $files = $request->getUploadedFiles()[$this->formName] ?? [];

        return $this->form->resolveForm($params, $files);
    }

    /**
     * Returns form parameters based on request method
     * 
     * @param ServerRequestInterface $request
     * 
     * @return array|null
     */
    private function getFormParameters(ServerRequestInterface $request): ?array
    {
        $requestMethod = $request->getMethod();

        if (strcasecmp($this->form->getFormMethod(), $requestMethod) !== 0) {
            return null;
        }
        if (strcasecmp($requestMethod, 'POST') === 0) {
            return $request->getParsedBody();
        }
        if (strcasecmp($requestMethod, 'GET') === 0) {
            return $request->getQueryParams();
        }

        return null;
    }

    public function buildForm(string $formFQCN, array|object|null $value = null): IFormBuilder
    {
        $copy = clone $this;

        $copy->valid = false;

        $form = $copy->invokeForm($formFQCN);
        $copy->fillFieldValues($form, $value);
        $copy->form = $form;
        $copy->form->build($copy);

        return $copy;
    }

    private function invokeForm(string $formFQCN): IFormElement
    {
        $formFQCN = '\\' . $formFQCN;
        
        if (!class_exists($formFQCN)) {
            throw new FormNotFoundException($formFQCN);
        }

        $reflection = new ReflectionClass($formFQCN);
        
        if (!$reflection->implementsInterface(IFormElement::class)) {
            throw new FormNotFoundException($formFQCN);
        }
        
        return (new ReflectionClass($formFQCN))->newInstanceWithoutConstructor();
    }

    private function fillFieldValues(IFormElement $form, array|object|null $value)
    {
        return $this->providerFactory->getProvider($value)
        ->fill($form, $value);
    }

    public function accessForm(): IFormElement
    {
        $this->checkFormValidity();

        return clone $this->form;
    }

    /**
     * Checks whether this form has been initialised
     * 
     * @throws FormBuilderException
     */
    private function checkFormValidity()
    {
        if ($this->form === null) {
            throw new FormBuilderException("Cannot process uninitalised form!");
        }
    }

}