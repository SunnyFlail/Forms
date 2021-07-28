<?php

namespace SunnyFlail\Forms\Interfaces;

use JsonSerializable;
use Psr\Http\Message\UploadedFileInterface;
use SunnyFlail\HtmlAbstraction\Interfaces\IElement;
use SunnyFlail\Forms\Interfaces\IFormBuilder;

/**
 * Basic interface for Forms
 */
interface IFormElement extends IMappableContainer, IElement, IWrapperField, JsonSerializable
{
    /**
     * Returns the name of the form
     * 
     * @return string
     */
    public function getName(): string;

    /**
     * Returns the HTTP method which this form responds to
     * 
     * @return string
     */
    public function getFormMethod(): string;

    /**
     * Checks form validity, changes field's values, errors
     * 
     * @param array $requestParams Parameters from request
     * @param UploadedFileInterface|UploadedFileInterface[] $uploadedFiles Files that were uploaded with request 
     * 
     * @return bool
     */
    public function resolveForm(array $requestParams, UploadedFileInterface|array $uploadedFiles): bool;

    /**
     * Builds the form
     * 
     * @param IFormBuilder $builder
     * 
     * @return void
     */
    public function build(IFormBuilder $builder);

    /**
     * Adds an error message
     * 
     * @param string $error Message to be displayed
     * 
     * @return IFormElement
     */
    public function addError(string $error): IFormElement;

    /**
     * Returns html attributes of this form
     * 
     * @return array
     */
    public function getAttributes(): array;

    /**
     * Returns stringified attributes the HTML tag will have
     * 
     * @return string
     */
    public function getHTMLAttributes(): string;
    
    /**
     * Returns Form's submit button element
     * 
     * @return IElement
     */
    public function getSubmitButton(): IElement;
}