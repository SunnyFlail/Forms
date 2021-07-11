<?php

namespace SunnyFlail\Forms\Interfaces;

interface IMapperFactory
{

    public function getMapper(mixed &$vessel): IValueMapper;

}