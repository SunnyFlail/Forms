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
     * @var int $requiredAmount How many files must be uploaded at least
     */
    protected int $requiredAmount;

    /**
     * @param IFileConstraint[] $constraints
     */
    public function __construct(
        string $name,
        int $inputCount = 1,
        int $required = 1,
        array $constraints = [],
        array $errorMessages = [],
        array $labelTexts = [],
        protected array $labelElements = [],
        protected array $containerAttributes = [],
        array $labelAttributes = [],
        protected array $inputAttributes = [],
        protected bool $terminateOnError = false
    ) {
        if ($inputCount < 1) {
            throw new FieldBuildingException("Input count can't be smaller than 1!");
        }
        if ($required > $inputCount) {
            throw new FieldBuildingException("Number of required files can't be bigger than input count!");
        }
        if ((!empty($labelTexts)) && (($count = count($labelTexts)) !== $inputCount)) {
            throw new FieldBuildingException(sprintf(
                'Field texts for all fields must be provided! Got %s, expected %s!',
                $count, $inputCount
            ));
        }

        $this->valid = null;
        $this->error = null;
        $this->value = null;
        $this->name = $name;
        $this->requiredAmount = $required;
        $this->required = boolval($required);
        $this->multiple = ($inputCount > 1);
        $this->inputCount = $inputCount;
        $this->labelTexts = $labelTexts;
        $this->constraints = $constraints;
        $this->labelAttributes = $labelAttributes;
        $this->errorMessages = $errorMessages;
    }

    public function jsonSerialize()
    {
        $options = [];
        $baseId = $this->getInputId();

        for ($i = 0; $i < $this->inputCount; $i++) {
            $required = ($i < $this->requiredAmount);
            $label = $this->labelTexts[$i] ?? $i;
            $id = $this->resolveId($baseId, $i);

            $options[$label] = [
                'id' => $id,
                'label' => $label,
                'required' => $required
            ];
        }

        $attributes = $this->inputAttributes;
        $attributes['type'] = 'file';

        return [
            'tagName' => 'INPUT',
            'name' => $this->getFullName(),
            'options' => $options,
            'valid' => $this->valid,
            'error' => $this->error,
            'multiple' => false,
            'attributes' => $attributes
        ];
    }

    public function getContainerElement(): IElement|array
    {
        $name = $this->getFullName();
        $baseId = $this->getInputId();
        $elements = [];

        for ($i = 0; $i < $this->inputCount; $i++) {
            $id = $this->resolveId($baseId, $i);
            $label = $this->createLabelElement($id, $i);
            $input = $this->createInputElement($id, $name, $i); 

            $elements[] = new ContainerElement(
                attributes: $this->containerAttributes,
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

            $inputs[] = $this->createInputElement($id, $name, $i);
        }

        return $inputs;
    }

    /**
     * Creates the LabelElement
     * 
     * @param string $id
     * @param int $repeat
     * 
     * @return LabelElement
     */
    protected function createLabelElement(string $id, int $repeat): LabelElement
    {
        $label = $this->labelTexts[$repeat] ?? $repeat;

        return new LabelElement(
            for: $id,
            labelText: $label,
            attributes: $this->labelAttributes
        );
    }

    /**
     * Creates the InputElement
     * 
     * @param string $id
     * @param string $name
     * @param int $repeat
     * 
     * @return FileElement 
     */
    protected function createInputElement(string $id, string $name, int $repeat): FileElement
    {
        $attributes = $this->inputAttributes;
        $attributes['required'] = ($repeat < $this->requiredAmount);

        return new FileElement(
            name: $name,
            id: $id,
            multiple: false,
            attributes: $attributes
        );
    }

}