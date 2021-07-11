<?php

namespace SunnyFlail\Forms\Interfaces;

interface IClassField extends IFieldElement, IMappableField
{
    
    /**
     * Returns the name of class 
     * 
     * @return string
     */
    public function getClassName(): string;

}