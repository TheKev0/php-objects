<?php
/**
 * This class encapsulates a form. It is an iterable collection of AbstractInput objects. 
 * It allows easily adding/removing and looping through form fields. When a form field is 
 * added it is indexed by it's name attribute. Note that an AbstractInput object uses the 
 * label as the name attribute if no name is provided, or generates a unique index for the
 * name if the label is an empty string. So if no name attribute is defined on an input field before 
 * it's added to the form, it is, in effect, indexed by it's label.
 * 
 * @package com.html
 * @author Kevork Sepetci
 * 
 * @todo method to get submit types directly
 * @todo make POST and GET class constants (maybe)
 * @todo add support for fieldsets
 * @todo add support for default tab indexing
 * @todo add support for different render formats (currently has just "Kev style")
 * @todo warn if enctype is not multipart/form-data and there is a file field. (And use states to set this attribute.)
 * @todo revise to work with Element's DOM navigation if necessary
 * @todo warn if enctype, method, or action are set via addtAttributes()
 */
class Form extends HTMLElement implements Iterator, ArrayAccess{
	
	/**
	 * Stores the input fields as an array of AbstractInput objects.
	 */
	protected $fields = array();
	
	/**
	 * A state variable to indicate whether the form has been submitted.
	 */
	protected $submitted = null;
	
	/**
	 * 
	 */
	protected $submittedValues = array();
	
	/**
	 * The action attribute of the form field
	 */
	protected $action = "";
	
	/**
	 * HTTP method used by the form. Defaults to POST
	 */
	protected $method;
	
	/**
	 * enctype attribute of the form. Set automatically by addField() if a file upload field is added.
	 */
	 protected $enctype = "application/x-www-form-urlencoded";
	
	/**
	 * Boolean variable to determine whether fields should be printed with a call to render()
	 */
	protected $printFields = true;
	
	/**
	 * Current position used to implement functions for the Iterator interface.
	 */
	protected $position = 0;
	
	private $setEnctypeCalled = false;
	
	/**
	 * Constructor: Create a form object to store AbstractInput form fields and automatically read submitted values from $_GET. $_POST
	 * WARNING: This class does NOT sanitize any form inputs. The client class must do this.
 	 * @param string $action (Optional): The action attribute (target URL) of the form. If not set assumes current path and query string.
	 * @param string $method (Optional): The HTTP method of the form. If not set, assumes POST. (change this ?)
	 */
	public function __construct($action=null, $method=null){
		if($action == null){
			$this->action = $_SERVER["PHP_SELF"];
			if(!empty($_SERVER["QUERY_STRING"])){
				$this->action .= "?" . $_SERVER["QUERY_STRING"];
			}
		}
		if($method == null){
			$this->method = "POST";
		}
		$this->setTagName("form");
		$this->setAttribute("method", $this->method);
		$this->setAttribute("action", $this->action);
	}
	
	/**
	 * Add a form field to the form. The form object uniquely identifies each form field by it's name attribute. (see Form->getName()) Note that if no name attribute is set on an AbstractInput object the label is used as the name. Thus if you add an input field that does not have a name attribute it will, in effect, be tracked by the label. Additionally, this function returns the index the Form is using track the input field. If a file upload field is added the enctype attribute is automatically set to "multipart/form-data" unless a call to setEnctype() has already been made. Finally, when Radio buttons with the same name are added to the form, a call to getField($name) will return an associative array of radio buttons with the same name. (see getField())
	 * @param AbstractInput $field the input field to add
	 * @return string the string the form uses to uniquely identify the form field. Can be passed to getField() to retrieve the form field.
	 * @see getField()
	 */
	public function addField(AbstractInput $field){
		//The Form used to track fields by Label. Now it tracks by name. See docblock.
		//$index = $field->getLabelString();
		//if(empty($index) || $index == null){
		//	$index = $field->getName();
		//}
		$index = $field->getName();
		if($field instanceof Radio){
			if(!isset($this->fields[$index])){
				$this->fields[$index] = array();
			}
			$this->fields[$index][$field->getValue()] = $field;
		}else{
			$this->fields[$index] = $field;
			if($field instanceof File && !$this->setEnctypeCalled){
				$this->setEnctype("multipart/form-data");
			}
		}
		return $index;
	}
	
	/**
	 * Check if a field with the given name attribute exists. Since forms often have many radio buttons with the same name, if there are ANY radio buttons with the $name provided this function will return true and false otherwise.
	 * @param string $name name attribute of field to get. Can also be a value returned by addField().
	 * @return boolean true if the field is in this form and false otherwise.
	 */
	public function fieldExists($name){
		$alreadyExists = array_key_exists($name, $this->fields);
		return $alreadyExists;
	}
	
	/**
	 * Retrieve a form field using it's name attribute or the value returned by addField().
	 * @param string $name the name attribute of the needed field or a value returned by addField()
	 * @return AbstractInput|boolean|array the form field or false if it is not present. If the desired field is a radio button an associative array of radio buttons with the provided $name is returned, indexed by the radio buttons' value attribute. To retrieve a single radio button the following syntax may be used <code>$form["name"]["value"]</code>
	 * @see getField()
	 */
	public function getField($name){
		if($this->fieldExists($name)){
			return $this->fields[$name];
		}
		return false;
	}
	
	/**
	 * Removes a form field using the name attribute or a value returned by addField(). WARNING: As there are often many radio buttons with the same name attribute, passing the name of a radio button will remove ALL radio buttons with the given name! To remove a single radio button use removeRadioButton().
	 * @param string $name the name attribute of the field to remove or a value returned by addField()
	 * @return AbstractInput|boolean the removed form field or false if it does not exist
	 * @see getField()
	 */
	public function removeField($name){
		if($this->fieldExists($name)){
			$field = $this->fields[$name];
			unset($this->fields[$name]);
			return $field;
		}
		return false;
	}
	
	/**
	 * Removes a single radio button from the Form. Since using removeField($name) removes all radio buttons with the given name, this function is used to remove only a single radio button.
	 * @param string $name the name attribute of the radio button to be removed
	 * @param string $value the value attribute of the radio button to be removed
	 * @return Radio|false the removed Radio button or false if it was not present
	 */
	public function removeRadioButton($name, $value){
		$radioButton = $this->fields[$name][$value];
		if($radioButton != null){
			unset($this->fields[$name][$value]);
			return $radioButton;
		}
		return false;
	}
	
	/**
	 * Get the action used by this form. (The URL it submits to.)
	 * @return string the action used by this form
	 */
	 public function getAction(){
	 	return $this->action;
	 }
	 
	 /**
	 * Set the action used by this form. (The URL it submits to.)
	 * @return string the previous action (URL or path) of this form.
	 * @param string $action the path or URL that the form should submit to
	 */
	 public function setAction($action){
	 	$previous = $this->action;
		$this->action = $action;
	 	return $previous;
	 }
	
	/**
	 * Get the HTTP method used by this form.
	 * @return string the HTTP method used by this form
	 * @todo change to return a class constant
	 */
	 public function getMethod(){
	 	return $this->method;
	 }
	 
	 /**
	 * Set the HTTP method used by this form.
	 * @return string the previous HTTP method used by this form, or false if parameter is messed up.
	 * @param string $method must be the string POST or GET
	 * @todo change to take method as a class constant
	 * @todo throw IlleglArgumentException
	 */
	 public function setMethod($method){
	 	$method = strtoupper($method);
	 	$previous = $this->method;
	 	if($method == "POST"){
	 		$this->method = $method;
	 		return $previous;
	 	}
	 	if($method == "GET"){
	 		$this->method = $method;
	 		return $previous;
	 	}
	 	return false;
	 }
	 
	/**
	 * Get the enctype attribute of this form.
	 * @return string the enctype attribute used by this form
	 */
	public function getEnctype(){
		return $this->enctype;
	}
	
	/**
	* Set the enctype attribute of this form.
	* @param string the enctype attribute to be used with this form
	* @return string the previous enctype attribute used by this form
	*/
	public function SetEnctype($enctype){
		$previous = $this->enctype;
		$this->enctype = $enctype;
		$this->setEnctypeCalled = true;
		return $previous;
	}
	 
	
	/**
	 * Check if this form was submitted. This method checks the appropriate 
	 * superglobal array $_GET|$_POST to see if the key for the key of the submit button 
	 * is present. This method will not work if the form has no submit button.
	 * @return boolean|string true if the form was submitted and false otherwise.
	 * @todo throw Exception if the form does not have a submit button.
	 */
	public function submitted(){
		if($this->submitted != null){
			return $this->submitted;
		}
		$hasSubmit = false;
		$submitFieldKeys = array();
		foreach($this->fields as $key => $field){
			if($field instanceof Submit){
				$hasSubmit = true;
				$submitFieldKeys[] = $key;
			}
		}
		if($hasSubmit === false){
			return false;	//throw exception here (IllegalStateException) no submit button.
		}
		
		$submittedFieldValue = false;
		if($this->method == "POST"){
			foreach($submitFieldKeys as $key){
				$submitButtonName = $this->fields[$key]->getName();
				if(isset($_POST[$submitButtonName])){
					$submittedFieldValue = $_POST[$submitButtonName];  
				}
			}
		}elseif($this->method == "GET"){
			foreach($submitFieldKeys as $key){
				$submitButtonName = $this->fields[$key]->getName();
				if(isset($_GET[$submitButtonName])){
					$submittedFieldValue = $_GET[$submitButtonName];  
				}
			}
		}
		$this->submitted = $submittedFieldValue;
		return $submittedFieldValue;
	}
	
	/**
	 * Get the data filled out in the form field. This method returns false if the form 
	 * was not submitted. (ie. submitted() returns false.)
	 * An associative array of the submitted values is returned with the same keys used with getField(), with the exception of Radio buttons.
	 * -Values for checkboxes are booleans (by default. see $formatNicely parameter)
	 * -Values for file upload fields are an associative array identical to $FILE["fieldName"] if a file was uploaded and false otherwise.
	 * -Keys for Radio buttons in the associative array are accessed by the name attribute 
	 * @param boolean $formatNicely (Optional): Defaults to true. When true method returns boolean values for checkboxes instead of the value attributes of the checkboxes.
	 * @return array|boolean An associative array of values filled out in the form or false if the form was not submitted.
	 */
	public function getSubmittedValues($formatNicely=true){
		if($this->submitted() === false){
			return false;
		}
		
		$returnValues = array();
		$globalArray = ($this->getMethod() == "POST") ? $_POST : $_GET;
		foreach($this->fields as $key => $field){
			$fieldName = $field->getName();
			$fieldName = $this->replaceSpaces($fieldName);		//Browsers replace spaces with underscoers.
			if($field instanceof Checkbox && $formatNicely){
				$returnValues[$key] = (isset($globalArray[$fieldName])) ? true : false;
			}elseif($field instanceof File){
				$returnValues[$key] = (empty($_FILES[$fieldName]["tmp_name"])) ? false : $_FILES[$fieldName];
			}elseif($field instanceof Radio){
				$returnValues[$fieldName] = (isset($globalArray[$fieldName])) ? $globalArray[$fieldName] : false;
			}
			else{
				if(isset($globalArray[$fieldName])){
					$returnValues[$key] = $globalArray[$fieldName];
				}
			}
		}
		$this->submittedValues = $returnValues;
		return $returnValues;
	}
	
	/**
	 * Load sumbitted values into the form field. This method returns false if the form 
	 * was not submitted.
	 * @return array|boolean An associative array of values filled out in the form (identical to the return value of getSubmittedValues()) or false if the form was not submitted.
	 */
	public function loadSubmittedValues(){
		if($this->submitted() === false){
			return false;
		}
		
		$globalArray = ($this->getMethod() == "POST") ? $_POST : $_GET;
		foreach($this->fields as $key => $field){
			$fieldName = $field->getName();
			$fieldName = $this->replaceSpaces($fieldName);		//Browsers replace spaces with underscoers.
			if($field instanceof Checkbox){
				$wasChecked = (isset($globalArray[$fieldName])) ? true : false;
				$this->fields[$key]->setChecked($wasChecked);
			}elseif($field instanceof Radio){
				if(isset($globalArray[$fieldName]) && $field->getValue() == $globalArray[$fieldName]){
					$field->setAttribute("checked", "checked");
				}
			}elseif($field instanceof Select){
				if(isset($globalArray[$fieldName])){
					$field->setSelected($globalArray[$fieldName]);
				}
			}elseif(!($field instanceof File || $field instanceof Submit) && isset($globalArray[$fieldName])){
				$field->setValue($globalArray[$fieldName]);
			}
		}
	}
	
	private function replaceSpaces($string, $char="_"){
		return str_replace(" ", $char, $string);
	}
	
	/**
	 * Check if the form is set to print it's input fields with a call to render()
	 * @return boolean true if form is set to print fields and false otherwise
	 */
	public function getPrintFields(){
		return $this->printFields;
	}
	 
	/**
	* Set whether or not the form will print input fields.
	* @param string $printFields the form will print all input fields if true and not if false
 	* @return string the previous value for printFields
	*/
	public function setPrintFields($printFields){
		$previous = $this->printFields;
		$this->printFields = $printFields;
	 	return $previous;
	}
	
	/**
	 * Prints the XML for the form. Has alias __toString().
	 * @param string $printKevStyle (Optional) wrap labales in span with class attribute 'label'. Defaults to false.
	 * @param string $breakString (Optional) The string to use as a line break between form elements. Defaults to "\n<br />"
	 * @return string A string representation of this form. ie. The HTML
	 */
	public function render($printKevStyle=true, $breakString= "\n<br />"){		
		$this->addAttribute("enctype", $this->enctype);
		$inner = "\n";
		if($this->printFields){
			foreach($this->fields as $key => $field){
				if(is_array($field)){	//this means we have a whole bunch of radio buttons w/ the same name attribute
					foreach($field as $value => $Element){
						if($printKevStyle){
							$this->setStyleRule("line-height", "2em;");
							$Element->setPrintLabel(false);
							$inner .= "<span class= \"label\" style= \"width: 250px;display: inline-block;text-align: right;margin-right: 10px;vertical-align: top;\">" . $Element->getLabelElement() . "</span>";
							$inner .= "<span>$Element</span><br />\n";
						}else{
							$inner .= "\t" . $field->render() . $breakString;
						}
					}
				}else{
					if($printKevStyle){
						$this->setStyleRule("line-height", "2em;");
						$field->setPrintLabel(false);
						$inner .= "<span class= \"label\" style= \"width: 250px;display: inline-block;text-align: right;margin-right: 10px;vertical-align: top;\">" . $field->getLabelElement() . "</span>";
						$inner .= "<span>$field</span><br />\n";
					}else{
						$inner .= "\t" . $field->render() . $breakString;
					}
				}
			}
			$this->setInnerText($inner);
		}
		return parent::render();
	}
	
/*
 * ArrayAccess methods here...
 */
	public function offsetExists($offset){
		return array_key_exists($offset, $this->fields);
	}
	
	public function offsetGet($offset){
		return $this->getField($offset);
	}
	
	/**
	 * @todo throw exception if value is not an AbstractInput
	 */
	public function offsetSet($offset, $value){
		if(!($value instanceof AbstractInput)){
			//throw exception
			return false;
		}
		$this->fields[$offset] = $value;
	}
	
	public function offsetUnset($offset){
		unset($this->fields[$offset]);
	}
 
 /*
 * Iterator methods here...
 */
	public function current(){
		$arrayKeys = array_keys($this->fields);
		return $this->fields[$arrayKeys[$this->position]];
	}
	
	public function key(){
		$arrayKeys = array_keys($this->fields);
		return $arrayKeys[$this->position];
	}
	
	public function next(){
		$this->position++;
	}
	
	public function rewind(){
		$this->position = 0;
	}
	
	public function valid(){
		$arrayKeys = array_keys($this->fields);
		if($this->position >= 0 && $this->position < count($arrayKeys)){
			return true;
		}
		return false;
	}
 	
}
?>
