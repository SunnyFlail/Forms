<?php

namespace SunnyFlail\Forms\Traits;

use SunnyFlail\HtmlAbstraction\Elements\ContainerElement;
use SunnyFlail\HtmlAbstraction\Elements\TextNodeElement;
use SunnyFlail\HtmlAbstraction\Interfaces\IElement;

trait ErrorTrait
{

    /**
     * @var string|null $error Message that is shown if this field is invalid
     */
    protected ?string $error;

    /**
     * @var array $errorAttributes Attributes to add to error Element
     */
    protected array $errorAttributes;

    public function getErrorElement(): ?IElement
    {
        if (null !== $this->error) {
            return new ContainerElement(
                attributes: $this->errorAttributes,
                nestedElements: [
                    new TextNodeElement($this->error)
                ]
            );
        }

        return null;
    }

}