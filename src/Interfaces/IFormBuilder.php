<?php

namespace SunnyFlail\Forms\Interfaces;

use SunnyFlail\HtmlAbstraction\Interfaces\IElement;
use Psr\Http\Message\ServerRequestInterface;
use SunnyFlail\Forms\Interfaces\IField;

interface IFormBuilder extends IElement
{

    /**
     * Adds a field to the Form
     * 
     * @return IFormBuilder
     * @throws FormBuilderException if form was initalised
     */
    public function add(IField $field): IFormBuilder;

    /**
     * Processes the form
     */
    public function processForm(ServerRequestInterface $request): bool;

    /**
     * Adds and error to the form
     * @param string $error Message to be displayed
     */
    public function addError(string $error);

    /**
     * Returns data scraped from form submition\
     * 
     * @return object|array
     */
    public function getProcessedData(): object|array;

    /**
     * Returns a copy of form builder with provided values mapped to fields
     * 
     * @return IFormBuilder
     */
    public function buildForm(string $formFQCN, array|object|null $value = null): IFormBuilder;

}