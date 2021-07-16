<?php

namespace SunnyFlail\Forms\Interfaces;

use SunnyFlail\HtmlAbstraction\Interfaces\IElement;
use Psr\Http\Message\ServerRequestInterface;
use SunnyFlail\Forms\Interfaces\IFormBuilder;

/**
 * Basic interface for Forms
 */
interface IFormElement extends IMappableField, IElement
{
    /**
     * Returns the name of the form
     * 
     * @return string
     */
    public function getName(): string;

    /**
     * Resolves the form
     * 
     * @return bool
     */
    public function resolveForm(ServerRequestInterface $request): bool;

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
     * @return void
     */
    public function addError(string $error);

    /**
     * Adds an element before fields button
     * 
     * @param IElement $element
     * 
     * @return void
     */
    public function addElementAtStart(IElement $element);

    /**
     * Adds an element before the form submition button
     * 
     * @param IElement $element
     * 
     * @return void
     */
    public function addElementInMiddle(IElement $element);

    /**
     * Adds an element After the form submition button
     * 
     * @param IElement $element
     * 
     * @return void
     */
    public function addElementAtEnd(IElement $element);

}