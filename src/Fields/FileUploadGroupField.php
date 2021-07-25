<?php

namespace SunnyFlail\Forms\Fields;

use SunnyFlail\Forms\Exceptions\FieldBuildingException;
use SunnyFlail\HtmlAbstraction\Elements\ContainerElement;
use SunnyFlail\HtmlAbstraction\Elements\FileElement;
use SunnyFlail\Forms\Interfaces\IFileConstraint;
use SunnyFlail\Forms\Interfaces\IInputField;
use SunnyFlail\Forms\Interfaces\IFileField;
use SunnyFlail\Forms\Traits\FileUploadFieldTrait;
use SunnyFlail\Forms\Traits\MultipleElementFieldTrait;
use SunnyFlail\HtmlAbstraction\Elements\LabelElement;
use SunnyFlail\HtmlAbstraction\Elements\NodeElement;
use SunnyFlail\HtmlAbstraction\Interfaces\IElement;

final class FileUploadGroupField implements IInputField, IFileField
{
    
    use FileUploadFieldTrait, MultipleElementFieldTrait;

    /**
     * @var int $inputCount Number of input elements to be rendered 
     */
    protected int $inputCount;
    /**
     * @var string[] $labelTexts Texts to be displayed instead of number in label,
     * if provided must have same number of values as stated in input count.
     * Must be an incremental array 
     */
    protected array $labelTexts;

    /**
     * @param IFileConstraint[] $constraints
     */
    public function __construct(
        string $name,
        bool $required = true,
        int $inputCount = 1,
        array $constraints = [],
        array $errorMessages = [],
        array $labelTexts = [],
        protected array $labelElements = [],
        protected array $wrapperAttributes = [],
        array $labelAttributes = [],
        protected array $inputAttributes = [],
        protected bool $terminateOnError = false
    ) {
        if ($inputCount < 1) {
            throw new FieldBuildingException("Input count can't be smaller than 1!");
        }

        if ((!empty($labelTexts)) && (($count = count($labelTexts)) !== $inputCount)) {
            throw new FieldBuildingException(sprintf(
                'Field texts for all fields must be provided! Got %s, expected %s!',
                $count, $inputCount
            ));
        }

        $this->valid = false;
        $this->error = null;
        $this->value = null;
        $this->name = $name;
        $this->required = $required;
        $this->multiple = ($inputCount > 1);
        $this->inputCount = $inputCount;
        $this->labelTexts = $labelTexts;
        $this->constraints = $constraints;
        $this->labelAttributes = $labelAttributes;
        $this->errorMessages = $errorMessages;
    }

    public function getContainerElement(): IElement|array
    {
        $name = $this->getFullName();
        $baseId = $this->getInputId();
        $elements = [];

        for ($i = 0; $i < $this->inputCount; $i++) {
            $id = $this->resolveId($baseId, $i);
            $label = $this->createLabelElement($id, $i);
            $input = $this->createInputElement($id, $name); 

            $elements[] = new ContainerElement(
                attributes: $this->wrapperAttributes,
                nestedElements: [
                    $label,
                    $input
                ]
            );
        }

        return $elements;
    }


    public function getLabelElement(): IElement|array
    {
        $baseId = $this->getInputId();
        $labels = [];
        
        for ($i = 0; $i < $this->inputCount; $i++) {
            $id = $this->resolveId($baseId, $i);

            $labels[] = $this->createLabelElement($id, $i);
        }

        return $labels;
    }

    public function getInputElement(): IElement|array
    {
        $name = $this->getFullName();
        $baseId = $this->getInputId();
        $inputs = [];

        for ($i = 0; $i < $this->inputCount; $i++) {
            $id = $this->resolveId($baseId, $i);

            $inputs[] = $this->createInputElement($id, $name);
        }

        return $inputs;
    }

    protected function createLabelElement(string $id, string $repeat): LabelElement
    {
        $label = $this->labelTexts[$repeat] ?? $repeat;

        return new LabelElement(
            for: $id,
            labelText: $label,
            attributes: $this->labelAttributes
        );
    }

    protected function createInputElement(string $id, string $name): FileElement
    {
        return new FileElement(
            name: $name,
            id: $id,
            multiple: false,
            attributes: $this->inputAttributes
        );
    }

}