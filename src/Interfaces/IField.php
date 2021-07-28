<?php

namespace SunnyFlail\Forms\Interfaces;

use JsonSerializable;
use SunnyFlail\Forms\Exceptions\FormFillingException;
use SunnyFlail\HtmlAbstraction\Interfaces\IElement;

interface IField extends JsonSerializable
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
     * Adds a reference to the parent form
     * 
     * @return IField
     */
    public function withForm(IFormElement $form): IField;

    /**
     * Adds an error message to the field
     * 
     * @return IField
     */
    public function withError(string $error): IField;

    /**
     * Adds a value to the field
     * 
     * @param mixed $value
     * 
     * @return IField
     * @throws FormFillingException 
     */
    public function withValue(mixed $value): IField;

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
     * 
     * @return mixed
     * @throws InvalidFieldException
     */
    public function getValue(): mixed;

    /**
     * Returns the occured error message,
     * associative array with field names as keys and error messages as keys,
     * or null if no error occurred
     * 
     * @return string|string[]/null
     */
    public function getError(): mixed;

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

    /**
     * Returns the input Element or an array of them
     * 
     * @return IElement|IElement[];
     */
    public function getInputElement(): IElement|array;

    /**
     * Returns the label Element or an array of them
     * 
     * @return IElement|IElement[]
     */
    public function getLabelElement(): IElement|array;

    /**
     * Returns the error Element or null if no error occurred
     * 
     * @return IElement|null
     */
    public function getErrorElement(): ?IElement;

}