<?php

namespace SunnyFlail\Forms\Interfaces;

interface IField
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
     * @return IField $this
     */
    public function withForm(IFormElement $form): IField;

    /**
     * Adds an error message to the field
     * 
     * @return IField $this
     */
    public function withError(string $error): IField;

    /**
     * Adds a value to the field
     * 
     * @return IField $this
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