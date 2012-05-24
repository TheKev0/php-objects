<?php
/**
 * Encapsulates a text box (<textarea>) in a form.
 * @package com.html.form
 * @author Kevork Sepetci
 */
class TextArea extends AbstractInput{
	
	/**
	 * Constructor: Create an instance of a text area form input field.
 	 * @param string $labelString The label of the input field. Appears in <label> tags.
	 * @param string $value The value attribute of the input field
	 * @param string $name (Optional) The name attribute of the input field. Defaults to same as label.
	 * @param string $id (Optional) The id attribute of the input field. Defaults to same as label.
	 */
	public function __construct($labelString, $value, $name=null, $id=null){
		$this->setIsInline(false);
		$this->setTagName("textarea");
		parent::__construct($labelString, $value, $name, $id);
	}
	
	/**
	 * Prints the markup for this input. Has alias __toString()
	 * @return string A string representation of this input. ie. The XML
	 */
	public function render(){
		$this->clearAttribute("value");
		$this->setInnerText($this->getValue());
		$input = parent::render();
		return $input;
	}

}

?>
