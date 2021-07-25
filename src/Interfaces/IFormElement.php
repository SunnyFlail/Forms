<?php

namespace SunnyFlail\Forms\Interfaces;

use SunnyFlail\HtmlAbstraction\Interfaces\IElement;
use Psr\Http\Message\ServerRequestInterface;
use SunnyFlail\Forms\Interfaces\IFormBuilder;

/**
 * Basic interface for Forms
 */
interface IFormElement extends IMappableField, IElement, IWrapperField
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
     * @return IFormElement
     */
    public function addError(string $error): IFormElement;

}