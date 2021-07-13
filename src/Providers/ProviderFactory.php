<?php

namespace SunnyFlail\Forms\Providers;

use SunnyFlail\Forms\FormBuilder\ArrayValueProvider;
use SunnyFlail\Forms\Interfaces\IProviderFactory;
use SunnyFlail\Forms\Interfaces\IValueProvider;
use SunnyFlail\Forms\Interfaces\IFormElement;

final class ProviderFactory implements IProviderFactory
{

    private IValueProvider $objectProvider;
    private IValueProvider $arrayProvider;
    private IValueProvider $nullProvider;

    public function getProvider($value): IValueProvider
    {
        if ($value === null) {
            if (!(isset($this->nullProvider))) {
                $this->nullProvider = new class implements IValueProvider {
                    public function fill(IFormElement $element, $value) {
                        return;
                    }
                };
            }
            return $this->nullProvider;
        }
        
        if (is_array($value)) {
            if (!(isset($this->arrayProvider))) {
                $this->arrayProvider = new ArrayValueProvider();
            }
            return $this->arrayProvider;
        }

        if (!(isset($this->objectProvider))) {
            $this->objectProvider = new ObjectValueProvider();
        }
        return $this->objectProvider;
    }

}