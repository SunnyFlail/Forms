<?php

namespace SunnyFlail\Forms\Traits;

use SunnyFlail\HtmlAbstraction\Elements\LabelElement;
use SunnyFlail\HtmlAbstraction\Interfaces\IElement;

/**
 * Trait for Elements that have a single input and label
 */
trait LabeledElementTrait
{
    
    use InputFieldTrait;

    /**
     * @var string|null Text to show in label
     */
    protected ?string $labelText;

    /**
     * @var array $labelAttributes Attributes to add to label Element
     */
    protected array $labelAttributes;

    public function getLabelElement(): IElement|array
    {
        return new LabelElement(
            for: $this->getInputId(),
            labelText: $this->labelText ?? $this->name,
            attributes: $this->labelAttributes,
        );
    }

}