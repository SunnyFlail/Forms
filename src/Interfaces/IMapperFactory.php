<?php

namespace SunnyFlail\Forms\Interfaces;

interface IMapperFactory
{
    /**
     * Returns a compatible Mapper
     * 
     * @param mixed $vessel Reference to variable that will be filled with data 
     * 
     * @throws \InvalidArgumentException if provided vessel is of incompatible type
     */
    public function getMapper(mixed &$vessel): IValueMapper;

}