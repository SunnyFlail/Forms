<?php

namespace SunnyFlail\Forms\Interfaces;

interface IClassField extends IField, IMappableField
{
    
    /**
     * Returns the name of class 
     * 
     * @return string
     */
    public function getClassName(): string;

}