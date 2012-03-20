<?php
/**
 * This class encapsulates a form. It is an iterable collection of AbstractInput objects. It allows easily adding/removing and looping through form fields. When a form field is added it is indexed is by default the label. If the provided label is blank an internally generated index is returned which can be used to retrieve the form input field.
 * @package com.form
 */
class Form extends Element{
	
	/**
	 * Stores the input fields as an array of AbstractInput objects.
	 */
	protected $fields = array();
	
	/**
	 * 
	 */
	protected $submittedValues = array();
	
	/**
	 * A state variable to indicate whether the form field values have been read from the SuperGloval arrays. ($_GET, $_POST)
	 */
	protected $hasReadValues = false;
	
	/**
	 * 
	 */
	protected $action = "";
	
	/**
	 * 
	 */
	protected $method;
	
	/**
	 * Constructor: Create a form object to store AbstractInput form fields and automatically read submitted values from $_GET. $_POST
	 * WARNING: This class does NOT sanitize any form inputs. The client class must do this.
 	 * @param string $action (Optional): The action attribute (target URL) of the form. If not set assumes current path and query string.
	 * @param string $method The HTTP method of the form. If not set, assumes POST. (change this ?)
	 */
	public function __construct($action=null, $method=null){
		if($action == null){
			$this->action = $_SERVER["PHP_SELF"] . "?" . $_SERVER["QUERY_STRING"];
		}
		if($method == null){
			$this->method = "POST";
		}
		$this->setTagName("form");
		$this->setAttribute("method", $this->method);
		$this->setAttribute("action", $this->action);
	}
	
	/**
	 * Add a form field to the form. The form uniquely identifies each form field by an index. By default the label is a form field's index. If the label is blank the addField function uses an internally generated value. This function always returns the index it is using so that form fields can be retrieved using getField($index).
	 * @param AbstractInput $field the input field to add
	 * @return string an index the form instance uses to uniquely identify the form field.
	 * @see getField()
	 */
	public function addField(AbstractInput $field){
		$index = $field->getLabelString();
		if(empty($index) || $index == null){
			$index = md5($field);
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
	 * Prints the XML for the form. Has alias __toString().
	 * @param string $breakBetweenFields (Optional) newline character to print between fields default: "<br />\t"
	 * @return string A string representation of this form. ie. The HTML
	 */
	public function render($breakBetweenFields=true){
		$inner = "\n";
		foreach($this->fields as $key => $field){
			$inner .= "\t" . $field->render() . "\n";
		}
		$this->setInnerHTML($inner);
		return parent::render();
	}
}
?>
