<?php

namespace SunnyFlail\Forms\Interfaces;

use SunnyFlail\HtmlAbstraction\Interfaces\IElement;

interface IInputField extends IField, IElement
{

    /**
     * Returns the name of input that will be used as attribute
     *
     * @example For field named 'text' inside form 'contact[]' it will be 'contact[text]' 
     * 
     * @return string
     */
    public function getFullName(): string;

    /**
     * Returns the ID attribute of the used input
     * 
     * @return string
     */
    public function getInputId(): string;

    /**
     * Returns the error message with provided code;
     * 
     * @return string
     */
    public function resolveErrorMessage(string $code): string;

}