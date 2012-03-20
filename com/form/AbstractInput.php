<?php
/**
 * This is an abstract subclass of Element that encapsulates an HTML or XForm input field. This class does not pertain specifically to HTML, XForms, etc. It is meant to be general and specific types of input fields are defined as children of this object.
 *	@package com.form
 */
abstract class AbstractInput extends Element{

	/**
	 * Name attribute of the input field.
	 */
	protected $name;
	
	/**
	 * ID attribute of the input field.
	 */
	protected $id;
	
	/**
	 * Holds the label of the input field.
	 */
	protected $label;
	
	/**
	 * Value attributes of the input field.
	 */
	protected $value;
	
	/**
	 * Boolean to control whether or not label tag will be printed.
	 */
	protected $printLabel;
	
	/**
	 * DEPRECATED: Name of validation function to run against value
	 * @deprecated
	 */
	protected $validation_function;
	
	/**
	 * Use as a default, internally generated name, id, etc. (auto-incremented)
	 */
	protected static $identifier = 0;
	
	/**
	 * Constructor to set the instance variables. Since this is an abstract class the constructor is never used explicitly. Only the child classes use this constructor.
	 * @param string $label The label of the input field. Appears in <label> tags.
	 * @param string $value The value attribute of the input field
	 * @param string $name (Optional) The name attribute of the input field. Defaults to same as label.
	 * @param string $id (Optional) The id attribute of the input field. Defaults to same as label.
	 */
	public function __construct($labelString, $value, $name=null, $id=null){
		$this->value = $value;
		$this->printLabel = true;
		if(empty($labelString)){
			$this->name = ($name == null) ? AbstractInput::$identifier : ($name);
			$this->id = ($id == null) ? AbstractInput::$identifier : $id;
		}else{
			$this->name = ($name == null) ? $labelString : ($name);
			$this->id = ($id == null) ? $labelString : $id;
		}
		$this->setAttribute("name", $this->name);
		$this->label = new Element("label", $labelString, "", array("for" => $this->name));
		AbstractInput::$identifier++;
	}
	
	/**
	 * Get the name attribute of this input field.
	 * @return string id of the input field
	 */
	public function getName(){
		return $this->name;
	}
	
	/**
	 * Set the name attribute for this input field.
	 * @param string $name name attribute
	 */
	public function setName($name){
		$this->name = $name;
	}
	
	/**
	 * Get the label Element of this input field.
	 * @return Element label of the input field
	 */
	public function getLabelElement(){
		return $this->label;
	}
	
	/**
	 * Set the label Element for this input field.
	 * @param Element $label label
	 * @return Element returns the old label Element
	 */
	public function setLabelElement(Element $label){
		$oldLabel = $this->label;
		$this->label = $label;
		return $oldLabel;
	}
	
	/**
	 * Get the label string of this input field.
	 * @return string label of the input field
	 */
	public function getLabelString(){
		return $this->label->getInnerHTML();
	}
	
	/**
	 * Set the label string for this input field.
	 * @param string $label string to set as label of input field
	 * @return string returns the old label string
	 */
	public function setLabelString($label){
		$oldLabel = $this->getLabelString();
		$this->label->setInnerHTML($label);
		return $oldLabel;
	}
	
	/**
	 * Check if the label tag is going to be included in the call to render()
	 * @return boolean label true if label will be printed, false otherwise.
	 */
	public function getPrintLabel(){
		return $this->printLabel;
	}
	
	/**
	 * Set the label tag to be included or excluded in the call to render()
	 * @param boolean $label true to print label, false otherwise
	 */
	public function setPrintLabel($printLabel){
		$this->printLabel = $includeLabel;
	}
	
	/**
	 * Get the value attribute of this input field.
	 * @return string value of the input field.
	 */
	public function getValue(){
		return $this->value;
	}
	
	/**
	 * Set the value attribute for this input field.
	 * @param string $val value attribute
	 */
	public function setValue($val){
		$this->value = $val;
	}
	
	/**
	 * DEPRECATED: Validates the value of an input field using a provided callback function. An arbitrary number of arguments can be passed to the callback function. The callback function's first parameter must be the value being validated because this function provides the value of the field as the first parameter automatically.
	 * @deprecated
	 * @param array $arguments This an array of the function name and it's arguments. The first element must be the callback function's name and the rest are the parameters to the function. The first parameter to the callback function (value being validated) must be omitted from this array.
	 */
	public function validate($arguments=array()){
		if(count($arguments) == 0){
			$arguments = $this->validate_function;
		}
		if(count($arguments) == 0){
			return true;
		}

		//call function and return results
		$function = $arguments[0];
		$args[0] = $this->value;
		$args = array_merge($args, array_slice($arguments, 1));
		return call_user_func_array($function, $args);
	}
	
	/**
	 * Prints the markup for this input. Has alias __toString()
	 * @return string A string representation of this input. ie. The XML
	 */
	public function render(){
		$input = parent::render();
		$label = ($this->getPrintLabel() == true) ? $this->getLabelElement() : "";
		return $label . $input;
	}

}
?>