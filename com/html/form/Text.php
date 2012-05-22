<?php
/**
 * Encapsulates a text input field in a form.
 * @package com.html.form
 * @author Kevork Sepetci
 */
class Text extends AbstractInput{	
	
	/**
	 * Constructor: Create an instance of a text input field.
 	 * @param string $labelString The label of the input field. Appears in <label> tags.
	 * @param string $value The value attribute of the input field
	 * @param string $name (Optional) The name attribute of the input field. Defaults to same as label.
	 * @param string $id (Optional) The id attribute of the input field. Defaults to same as label.
	 */
	public function __construct($labelString, $value, $name=null, $id=null){
		$this->setIsInline(true);
		$this->setTagName("input");
		$this->setAttribute("type", "text");
		parent::__construct($labelString, $value, $name, $id);
	}
	
}
?>
