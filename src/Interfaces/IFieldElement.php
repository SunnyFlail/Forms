<?php

namespace SunnyFlail\Forms\Interfaces;

use SunnyFlail\HtmlAbstraction\Interfaces\IElement;

interface IFieldElement extends IElement
{

    /**
     * Checks whether provided value fits with constraints
     * and scrapes data
     * 
     * @param array $values
     * 
     * @return bool
     */
    public function resolve(array $values): bool;

    /**
     * Adds an error message to the field
     * 
     * @return IFieldElement $this
     */
    public function withError(string $error): IFieldElement;

    /**
     * Adds a reference to the parent form
     * 
     * @return IFieldElement $this
     */
    public function withForm(IFormElement $form): IFieldElement;

    /**
     * Adds a value to the form AND sets it to be valid
     * 
     * @return IFieldElement $this
     */
    public function withValue(mixed $value): IFieldElement;

    /**
     * Returns the name of input INSIDE the form
     *
     * @example For field named 'text' inside form 'contact[]' it will be 'text' 
     * Field name MUST correspond to name of property for mapped class OR associative array field
     * 
     * @return string
     */
    public function getName(): string;

    /**
     * Returns the user provided value
     * 
     * MUST be called AFTER resolve
     */
    public function getValue();

    /**
     * Checks whether this field must be filled
     * 
     * @return bool
     */
    public function isRequired(): bool;

    /**
     * Checks whether this field was validated successfully
     *
     * @return bool 
     */
    public function isValid(): bool;

}