# Forms
A simple Form Abstraction layer
# 1 Creating forms
First create a class extending [`SunnyFlail\Forms\Form\FormElement`](src/Form/FormElement.php) class.
It must implement method [`IFormElement::build`](src/Interfaces/IFormElement.php).
```php
use SunnyFlail\Forms\Form\FormElement;
use SunnyFlail\Forms\Interfaces\IFormBuilder;

class ConcreteForm extends FormElement
{

    public function build(IFormBuilder $builder)
    {
        (...)
    }

}
```
## 1.1 Adding Fields
Inside the `IFormElement::build` method invoke [`IFormBuilder::add`](src/Interfaces/IFormBuilder.php), providing the field you want to add as an argument. You can chain this method.
```php
$builder->add(new InputField('text'));
```
## 1.2 Configuring form
Inside the `` method you can change properties  
*(Mandatory)* `string $formName` - Name of the form - prefix which will be provided to form's field names  
`string $formMethod` - HTTP method which this form will use (defaults to **GET**)  
`string $buttonText` - Text which will be shown inside the submit button  
`array $attributes` - Atttributes to be provided to the form Element  
`array buttonAttributes` - Attributes provided to submit button Element of the form  
`IElement[] $topElements`- Array of objects implementing `SunnyFlail\HtmlAbstraction\Interfaces\IElement` interface - Elements to be printed before all fields   
`IElement[] $middleElements` - Array of objects implementing `SunnyFlail\HtmlAbstraction\Interfaces\IElement` interface - Elements to be printed before after fields, before button  
`IElement[] $bottomElements` - Array of objects implementing `SunnyFlail\HtmlAbstraction\Interfaces\IElement` interface - Elements to be printed after submit button  
`string|null $classFQCN` - FQCN of (Plain Old Php Object) class to which this form will resolve to, resolves to **array** if this is null  
`bool $useHtmlValidation` - Should this form use Html Validation (defaults to **true**)  
`bool $withFiles` If set to true sets the enctype (encoding type) to multipart/form-data  

# 2 Using forms
# 2.1 Creating builder
First you need to create a global copy of form builder
```php
$objectCreator = new SunnyFlail\ObjectCreator\ObjectCreator();
$valueMapper = new SunnyFlail\Forms\Mappers\ValueMapper($objectCreator);
$valueProviderFactory = new SunnyFlail\Forms\Providers\ProviderFactory();

$builder = new SunnyFlail\Forms\Form\FormBuilder($valueMapper, $valueProviderFactory);
```
# 2.2 Building form
Then invoke [`IFormBuilder::buildForm`](src/Interfaces/IFormBuilder.php)
method providing form FQCN as first argument, and optionally object/array to scrape values from as second.  
This returns a copy of Builder so prepare another variable for it  
```php
$concreteFormBuilder = $builder->buildForm(ConcreteForm::class);
```
# 2.3 Processing user input
To process user provided data invoke [`IFormBuilder::processForm`](src/Interfaces/IFormBuilder.php)
using a object implementing `Psr\Http\Message\ServerRequestInterface` interface as an argument  
This will return a bool indicating whether form got valid values  
```php
if ($concreteFormBuilder->processForm($request)) {
    (...)
}
```
# 2.4 Getting values
To get values scraped from form elements use [`IFormBuilder::getProcessedData`](src/Interfaces/IFormBuilder.php)  
```php
$values = $concreteFormBuilder->getProcessedData();
```
# 2.5 Adding errors
To add en error to form use [`IFormBuilder::addError`](src/Interfaces/IFormBuilder.php) 
```php
$concreteFormBuilder->addError('An error occurred!');
```
# 2.6 Rendering form
You can either just stringify (eg. `echo $concreteFormBuilder;`) the form OR manually render all of the fields.  
To render the form manually, first you need to get a copy of Form by calling [`IFormBuilder::accessForm`](src/Interfaces/IFormBuilder.php)  
To get HTML form tag attributes call [`IFormElement::getHTMLAttributes`](src/Interfaces/IFormElement.php)  
To get an associative array of fields call [`IFormElement::getFields`](src/Interfaces/IFormElement.php)  
To get Fields Input element call [`IField::getInputElement`](src/Interfaces/IField.php)  
To get Fields Label element call [`IField::getLabelElement`](src/Interfaces/IField.php)  
Those methods may return an IElement OR and array of them  
If an error occured you can get the error element with [`IField::getErrorElement`](src/Interfaces/IField.php) 

# 3 Available fields

## 3.1 InputField
This is representation of [`<input type="(...)">`](src/Fields/InputField.php)

```php
$input = new SunnyFlail\Forms\Fields\InputField();
```
Input field constructor takes parameters:  

`string $name` - Name of the field  
`string $type` - Field type  
`bool $required`- Whether this field must be filled  
`bool $rememberValue` - Whether this field should retain provided value of error  
`IConstraint[] $constraints` - Array of objects implementing `SunnyFlail\Constraints\Interfaces\IConstraint` interface  
`array $errorMessages` - Array of strings, keys must be numeric strings, '-1' is for no value error, positive keys are for failed constraint errors  
`IElement[] $topElements`- Array of objects implementing `SunnyFlail\HtmlAbstraction\Interfaces\IElement` interface - Elements to be printed before label  
`IElement[] $middleElements` - Array of objects implementing `SunnyFlail\HtmlAbstraction\Interfaces\IElement` interface - Elements to be printed before input  
`IElement[] $bottomElements` - Array of objects implementing `SunnyFlail\HtmlAbstraction\Interfaces\IElement` interface - Elements to be printed before error  
`array $inputAttributes` - Array of html attributes to be provided to input Element  
`array $containerAttributes` - Array of html attributes to be provided to wrapper Element  
`array $errorAttributes` - Array of html attributes to be provided to error Element  
`?string $labelText` - Text to be shown inside label, if unset it shows the field name  
`array $labelAttributes` - Array of html attributes to be provided to label Element  

## 3.2 EmailField
This is representation of [`<input type="email">`](src/Fields/EmailField.php)
```php
$input = new SunnyFlail\Forms\Fields\EmailField();
```
Email field constructor takes parameters  
`string $name` - Name of the field  
`bool $required`- Whether this field must be filled  
`bool $rememberValue` - Whether this field should retain provided value of error  
`array $errorMessages` - Array of strings, keys must be numeric strings, '-1' is for no value error, positive keys are for failed constraint errors  
`IElement[] $topElements`- Array of objects implementing `SunnyFlail\HtmlAbstraction\Interfaces\IElement` interface - Elements to be printed before label  
`IElement[] $middleElements` - Array of objects implementing `SunnyFlail\HtmlAbstraction\Interfaces\IElement` interface - Elements to be printed before input  
`IElement[] $bottomElements` - Array of objects implementing `SunnyFlail\HtmlAbstraction\Interfaces\IElement` interface - Elements to be printed before error  
`array $inputAttributes` - Array of html attributes to be provided to input Element  
`array $containerAttributes` - Array of html attributes to be provided to wrapper Element  
`array $errorAttributes` - Array of html attributes to be provided to error Element  
`?string $labelText` - Text to be shown inside label, if unset it shows the field name  
`array $labelAttributes` - Array of html attributes to be provided to label Element  

## 3.3 PasswordField
This is representation of [`<input type="password">`](src/Fields/PasswordField.php)
```php
$input = new SunnyFlail\Forms\Fields\PasswordField();
```
This field introduces `Peeper` - a button which, when coupled with appriopriate JS, shows the password provided to the field

Password field constructor takes parameters:  

`string $name` - Name of the field  
`bool $required`- Whether this field must be filled  
`bool $rememberValue` - Whether this field should retain provided value of error  
`IConstraint[] $constraints` - Array of objects implementing `SunnyFlail\Constraints\Interfaces\IConstraint` interface  
`array $errorMessages` - Array of strings, keys must be numeric strings, '-1' is for no value error, positive keys are for failed constraint errors  
`IElement[] $topElements`- Array of objects implementing `SunnyFlail\HtmlAbstraction\Interfaces\IElement` interface - Elements to be printed before label  
`IElement[] $middleElements` - Array of objects implementing `SunnyFlail\HtmlAbstraction\Interfaces\IElement` interface - Elements to be printed before input  
`IElement[] $bottomElements` - Array of objects implementing `SunnyFlail\HtmlAbstraction\Interfaces\IElement` interface - Elements to be printed before error  
`bool $withPeeper` - Whether this field should be rendered with an peeper  
`array $inputAttributes` - Array of html attributes to be provided to input Element  
`array $peeperAttributes` - Array of html attributes to be provided to peeper Element  
`array $containerAttributes` - Array of html attributes to be provided to wrapper Element  
`array $errorAttributes` - Array of html attributes to be provided to error Element  
`?string $labelText` - Text to be shown inside label, if unset it shows the field name  
`array $labelAttributes` - Array of html attributes to be provided to label Element  

## 3.4 TextAreaField
This is representation of [`<textarea></textarea>`](src/Fields/TextAreField.php)
```php
$input = new SunnyFlail\Forms\Fields\TextAreaField();
```
TextArea field constructor takes parameters:  

`string $name` - Name of the field  
`bool $required`- Whether this field must be filled  
`bool $rememberValue` - Whether this field should retain provided value of error  
`IConstraint[] $constraints` - Array of objects implementing `SunnyFlail\Constraints\Interfaces\IConstraint` interface  
`array $errorMessages` - Array of strings, keys must be numeric strings, '-1' is for no value error, positive keys are for failed constraint errors  
`IElement[] $topElements`- Array of objects implementing `SunnyFlail\HtmlAbstraction\Interfaces\IElement` interface - Elements to be printed before label  
`IElement[] $middleElements` - Array of objects implementing `SunnyFlail\HtmlAbstraction\Interfaces\IElement` interface - Elements to be printed before input  
`IElement[] $bottomElements` - Array of objects implementing `SunnyFlail\HtmlAbstraction\Interfaces\IElement` interface - Elements to be printed before error  
`array $inputAttributes` - Array of html attributes to be provided to input Element  
`array $containerAttributes` - Array of html attributes to be provided to wrapper Element  
`array $errorAttributes` - Array of html attributes to be provided to error Element  
`?string $labelText` - Text to be shown inside label, if unset it shows the field name  
`array $labelAttributes` - Array of html attributes to be provided to label Element  

## 3.5 SelectField
This is representation of [`<select>(...)</select>`](src/Fields/SelectField.php)
```php
$input = new SunnyFlail\Forms\Fields\SelectField();
```
Select field constructor takes parameters:  

`string $name` - Name of the field  
`array $options` - Options to render - 
`bool $required`- Whether this field must be filled  
`bool $rememberValue` - Whether this field should retain provided value of error  
`bool $multiple` - Should this field allow multiple values  
`bool $useIntristicValues` - Should this only check for values provided in $options parameter or should accept any value matching provided constraints  
`IConstraint[] $constraints` - Array of objects implementing `SunnyFlail\Constraints\Interfaces\IConstraint` interface  
`array $errorMessages` - Array of strings, keys must be numeric strings, '-1' is for no value error, positive keys are for failed constraint errors  
`IElement[] $topElements`- Array of objects implementing `SunnyFlail\HtmlAbstraction\Interfaces\IElement` interface - Elements to be printed before label  
`IElement[] $middleElements` - Array of objects implementing `SunnyFlail\HtmlAbstraction\Interfaces\IElement` interface - Elements to be printed before input  
`IElement[] $bottomElements` - Array of objects implementing `SunnyFlail\HtmlAbstraction\Interfaces\IElement` interface - Elements to be printed before error  
`array $inputAttributes` - Array of html attributes to be provided to input Element  
`array $containerAttributes` - Array of html attributes to be provided to wrapper Element  
`array $errorAttributes` - Array of html attributes to be provided to error Element  
`?string $labelText` - Text to be shown inside label, if unset it shows the field name  
`array $optionAttributes` - Array of html attributes to be provided to option Element  
`array $labelAttributes` - Array of html attributes to be provided to label Element  

## 3.6 CheckBoxGroupField
This is representation of a group of [`<input type="checkbox">`](src/Fields/CheckBoxGroupField.php)
```php
$input = new SunnyFlail\Forms\Fields\CheckBoxGroupField();
```
Checkbox field constructor takes parameters:  

`string $name` - Name of the field  
`array $options` - Options to render - 
`bool $required`- Whether this field must be filled  
`bool $rememberValue` - Whether this field should retain provided value of error  
`bool $multiple` - Should this field allow multiple values  
`bool $useIntristicValues` - Should this only check for values provided in $options parameter or should accept any value matching provided constraints  
`IConstraint[] $constraints` - Array of objects implementing `SunnyFlail\Constraints\Interfaces\IConstraint` interface  
`array $errorMessages` - Array of strings, keys must be numeric strings, '-1' is for no value error, positive keys are for failed constraint errors  
`array $inputAttributes` - Array of html attributes to be provided to input Element  
`array $containerAttributes` - Array of html attributes to be provided to wrapper Element  
`array $errorAttributes` - Array of html attributes to be provided to error Element  
`array $labelAttributes` - Array of html attributes to be provided to label Element  

## 3.7 RadioGroupField
This is representation of a group of [`<input type="radio">`](src/Fields/RadioGroupField.php)
```php
$input = new SunnyFlail\Forms\Fields\RadioGroupField();
```
Radio field constructor takes parameters:  

`string $name` - Name of the field  
`array $options` - Options to render - 
`bool $required`- Whether this field must be filled  
`bool $rememberValue` - Whether this field should retain provided value of error  
`bool $useIntristicValues` - Should this only check for values provided in $options parameter or should accept any value matching provided constraints  
`IConstraint[] $constraints` - Array of objects implementing `SunnyFlail\Constraints\Interfaces\IConstraint` interface  
`array $errorMessages` - Array of strings, keys must be numeric strings, '-1' is for no value error, positive keys are for failed constraint errors  
`array $inputAttributes` - Array of html attributes to be provided to input Element  
`array $containerAttributes` - Array of html attributes to be provided to wrapper Element  
`array $errorAttributes` - Array of html attributes to be provided to error Element  
`array $labelAttributes` - Array of html attributes to be provided to label Element  

## 3.8 RepeatedInputField
[A field containing two fields whose values must be the same](src/Fields/RepeatedInputField.php)
```php
$input = new SunnyFlail\Forms\Fields\RepeatedInputField();
```
Repeated field constructor takes parameters:  

`IInputField $field` - First field  
`IInputField $repeatedField` - Repeated field  
`string $missmatchError` - Message to be displayed on error  

## 3.9 ClassMappedField
[A group of fields whose values will be mapped to an Plain Old Php Object](src/Fields/ClassMappedField.php)   
```php
$input = new SunnyFlail\Forms\Fields\ClassMappedField();
```
Class mapping field constructor takes parameters:  

`string $fieldName` - Name of the field  
`string $classFQCN` - Class name  
`IField ...$fields` - Fields with names defaulting to Class property names  

## 3.10 FileUploadField
This is representation of [`<input type="file">`](src/Fields/FileUploadField.php)

File upload field constructor takes parameters:  

`string $name` - Name of the field  
`bool $required`- Whether this field must be filled  
`bool $multiple` - Should this field allow multiple values  
`IFileConstraint[] $constraints` - Array of objects implementing `SunnyFlail\Constraints\Interfaces\IFileConstraint` interface  
`IElement[] $topElements`- Array of objects implementing `SunnyFlail\HtmlAbstraction\Interfaces\IElement` interface - Elements to be printed before label  
`IElement[] $middleElements` - Array of objects implementing `SunnyFlail\HtmlAbstraction\Interfaces\IElement` interface - Elements to be printed before input  
`IElement[] $bottomElements` - Array of objects implementing `SunnyFlail\HtmlAbstraction\Interfaces\IElement` interface - Elements to be printed before error  
`array $errorMessages` - Array of strings, keys must be numeric strings, '-1' is for no value error, positive keys are for failed constraint errors  
`array $inputAttributes` - Array of html attributes to be provided to input Element  
`array $containerAttributes` - Array of html attributes to be provided to wrapper Element  
`array $errorAttributes` - Array of html attributes to be provided to error Element  
`?string $labelText` - Text to be shown inside label, if unset it shows the field name  
`array $labelAttributes` - Array of html attributes to be provided to label Element  
`bool $terminateOnError` - Whether http upload error of one of the files should make this field invalid

## 3.11 FileUploadGroupField [from version ^1.3]
[A group of file upload fields](src/Fields/FileUploadGroupField.php)   

File upload field constructor takes parameters:  

`string $name` - Name of the field  
`int $inputCount` - How many inputs should this render - must be at least 1, if set to 1 this fields multiple property is set to false  
`int $required`- Minimal number of required files  
`IFileConstraint[] $constraints` - Array of objects implementing `SunnyFlail\Constraints\Interfaces\IFileConstraint` interface   
`array $errorMessages` - Array of strings, keys must be numeric strings, '-1' is for no value error, positive keys are for failed constraint errors  
`string[] $labelTexts` - Text to be shown inside label, If set must be an incremental array with same amout of keys as set in $inputCount, otherwise shows numbers  
`array $inputAttributes` - Array of html attributes to be provided to input Element  
`array $containerAttributes` - Array of html attributes to be provided to wrapper Element  
`array $errorAttributes` - Array of html attributes to be provided to error Element  
`array $labelAttributes` - Array of html attributes to be provided to label Element  
`bool $terminateOnError` - Whether http upload error of one of the files should make this field invalid

# 4 TODO
I need to add an `IFormBuilder::getRawValues` which would return an associative array of raw values provided to fields
