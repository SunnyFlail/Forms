<?php

namespace SunnyFlail\Forms\Interfaces;

interface ISelectableField extends IField
{

    /**
     * Fills the Field with possible Options
     * 
     * @var string[]|int[]|bool[]/array[] $values Keys will serve as a label for values,
     *                                    value if key is numeric
     * 
     * @return SelectElement
     */
    public function withOptions(array $options): ISelectableField;

}