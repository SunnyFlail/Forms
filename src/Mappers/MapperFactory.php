<?php

namespace SunnyFlail\Forms\Mappers;

use InvalidArgumentException;
use SunnyFlail\Forms\Interfaces\IMapperFactory;
use SunnyFlail\Forms\Interfaces\IValueMapper;

final class MapperFactory implements IMapperFactory
{
    private IValueMapper $arrayMapper;
    private IValueMapper $objectMapper;

    public function getMapper(mixed &$vessel): IValueMapper
    {
        if (is_array($vessel)) {
            if (!isset($this->arrayMapper)) {
                $this->arrayMapper = new ArrayMapper();
            }
            return $this->arrayMapper;
        }
        if (is_object($vessel)) {
            if (!isset($this->objectMapper)) {
                $this->objectMapper = new ObjectMapper();
            }
            return $this->objectMapper;
        }

        throw new InvalidArgumentException(sprintf(
            "Provided vessel is of incompatible type %s!", gettype($vessel)
        ));
    }

}