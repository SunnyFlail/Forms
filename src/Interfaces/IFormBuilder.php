<?php

namespace SunnyFlail\Forms\Interfaces;

use SunnyFlail\Forms\Exceptions\FormBuilderException;
use SunnyFlail\HtmlAbstraction\Interfaces\IElement;
use Psr\Http\Message\ServerRequestInterface;

interface IFormBuilder extends IElement
{

    /**
     * Adds a field to the Form
     * 
     * @return IFormBuilder
     * 
     * @throws FormBuilderException
     */
    public function add(IField $field): IFormBuilder;

    /**
     * Adds an element before fields button
     * 
     * @param IElement $element
     * 
     * @return IFormBuilder
     * 
     * @throws FormBuilderException
     */
    public function addElementAtStart(IElement $element): IFormBuilder;

    /**
     * Adds an element before the form submition button
     * 
     * @param IElement $element
     * 
     * @return IFormBuilder
     * 
     * @throws FormBuilderException
     */
    public function addElementInMiddle(IElement $element): IFormBuilder;

    /**
     * Adds an element After the form submition button
     * 
     * @param IElement $element
     * 
     * @return IFormBuilder
     * 
     * @throws FormBuilderException
     */
    public function addElementAtEnd(IElement $element): IFormBuilder;

    /**
     * Adds and error to the form
     * @param string $error Message to be displayed
     * 
     * @return IFormBuilder
     * 
     * @throws FormBuilderException
     */ 
    public function addError(string $error): IFormBuilder;

    /**
     * Returns data scraped from form submition\
     * 
     * @return object|array
     * 
     * @throws FormBuilderException
     */
    public function getProcessedData(): object|array;

    /**
     * Returns a copy of form builder with provided values mapped to fields
     * 
     * @return IFormBuilder
     */
    public function buildForm(string $formFQCN, array|object|null $value = null): IFormBuilder;

    /**
     * Processes the form
     * 
     * @param ServerRequestInterface $request
     * 
     * @return bool
     * 
     * @throws FormBuilderException
     */
    public function processForm(ServerRequestInterface $request): bool;

}