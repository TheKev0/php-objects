<?php
/**
 * This class encapsulates a form. It is an iterable collection of AbstractInput objects. It allows easily adding/removing and looping through form fields. When a form field is added it is indexed is by default the label. If the provided label is blank an internally generated index is returned which can be used to retrieve the form input field.
 * @package com.form
 * @todo if no submit button, add a hidden input field to act as a submit.
 * @todo method to get submit fields directly
 * @todo make POST and GET class constants
 * @todo add support for enctype
 * @todo add support for fieldsets
 * @todo add support for default tab indexing
 * @todo add support for different render formats
 */
class Form extends Element implements Iterator, ArrayAccess{
	
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
	 * 
	 */
	protected $action = "";
	
	/**
	 * HTTP method used by the form. Defaults to POST
	 */
	protected $method;
	
	/**
	 * Boolean variable to determine whether fields should be printed with a call to render()
	 */
	protected $printFields = true;
	
	/**
	 * Current position used to implement functions for the Iterator interface.
	 */
	protected $position = 0;
	
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
	 * Add a form field to the form. The form object uniquely identifies each form field by it's label. (@see Form::getName()) If the label string is blank, then the name attribute is used.
	 * @param AbstractInput $field the input field to add
	 * @return string the string the form uses to uniquely identify the form field. Can be passed to getField() to retrieve the form field.
	 * @see getField()
	 */
	public function addField(AbstractInput $field){
		$index = $field->getLabelString();
		if(empty($index) || $index == null){
			$index = $field->getName();
		}
		$this->fields[$index] = $field;
		return $index;
	}
	
	/**
	 * Check of a field with label $labelString exists.
	 * @param string $labelString label to to check. Can also be a value returned by addField()
	 * @return boolean true if the field is in this form and false otherwise.
	 */
	public function fieldExists($labelString){
		$alreadyExists = array_key_exists($labelString, $this->fields);
		return $alreadyExists;
	}
	
	/**
	 * Retrieve a form field using the label string or index returned by addField()
	 * @param string $labelString either the label string or index returned by addField()
	 * @return AbstractInput|boolean the form field or false if it is not present
	 * @see getField()
	 */
	public function getField($labelString){
		if($this->fieldExists($labelString)){
			return $this->fields[$labelString];
		}
		return false;
	}
	
	/**
	 * Removes a form field using the label string or index returned by addField()
	 * @param string $labelString either the label string or index returned by addField()
	 * @return AbstractInput|boolean the removed form field or false if it does not exist
	 * @see getField()
	 */
	public function removeField($labelString){
		if($this->fieldExists($labelString)){
			$field = $this->fields[$labelString];
			unset($this->fields[$labelString]);
			return $field;
		}
		return false;
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
		
		$inner = "\n";
		if($this->printFields){
			foreach($this->fields as $key => $field){
				if($printKevStyle){
					$field->setPrintLabel(false);
					$inner .= "<span class= \"label\">" . $field->getLabelElement() . "</span>";
					$inner .= "<span>$field</span><br />\n";
				}else{
					$inner .= "\t" . $field->render() . $breakString;
				}
			}
			$this->setInnerHTML($inner);
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
