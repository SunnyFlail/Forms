<?php

namespace SunnyFlail\Forms\Interfaces;

use Psr\Http\Message\ServerRequestInterface;
use SunnyFlail\Forms\Form\IFormBuilder;
use SunnyFlail\HtmlAbstraction\Interfaces\IElement;

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

}