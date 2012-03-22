<?php
/**
 * Encapsulates a Radio buttom form input field.
 * @package com.form.html
 */
class Radio extends AbstractInput{
	
	/**
	 * A boolean value indicating if this radio button is checked by default.
	 */
	private $checked;
	
	/**
	 * Constructor: Create an instance of a radio button form input field.
 	 * @param string $labelString The label of the input field. Appears in <label> tags.
	 * @param string $value The value attribute of the input field
	 * @param boolean $checked (Optional) Default checked/unchecked state of checkbox (true = checked). Normally unchecked
	 * @param string $name (Optional) The name attribute of the input field. Defaults to same as label.
	 * @param string $id (Optional) The id attribute of the input field. Defaults to same as label.
	 */
	public function __construct($labelString, $value, $checked=false, $name=null, $id=null){
		$this->setIsInline(true);
		$this->setTagName("input");
		$this->setAttribute("type", "radio");
		$this->checked = $checked;
		parent::__construct($labelString, $value, $name, $id);
	}
	
	/**
	 * Check if this checkbox instance is checked off by default.
	 * @return boolean true if this checkbox is checked, false otherwise.
	 */
	public function isChecked(){
		return $this->checked;
	}
	
	/**
	 * Set this checkbox as checked or unchecked.
	 * @param boolean $checked true to make checkbox checked, false otherwise.
	 * @return boolean returns the previous state
	 */
	public function setChecked($checked){	//paramater checked is a boolean inidcating whether button is checked
		$previous = $this->isChecked();
		$this->checked = $checked;
		return $previous;
	}

}
?>
