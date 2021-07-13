<?php

namespace SunnyFlail\Forms\Interfaces;

/**
 * Basic interface for classes that provide form fields with values
 */
interface IValueProvider
{

    /**
     * Fills form fields with provided values
     * 
     * @param IFormElement $form  Form to fill
     * @param mixed        $valus Values to fill it with
     * 
     * @return void
     */
    public function fill(IFormElement $form, mixed $values);

}