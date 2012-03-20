<?php
/**
 * Encapsulates a Checkbox form input field.
 * @package com.form
 */
class Checkbox extends AbstractInput{
	
	/**
	 * A boolean value indicating if this checkbox is checked or not.
	 */
	private $checked;	//if the radio button will be selected by default
	
	/**
	 * Constructor: Create an instance of a Checkbox object.
 	 * @param string $label The label of the input field. Appears in <label> tags.
	 * @param string $value The value attribute of the input field
	 * @param boolean $checked Default state checked/unchecked of checkbox (true = checked)
	 * @param string $name (Optional) The name attribute of the input field. Defaults to same as label.
	 * @param string $id (Optional) The id attribute of the input field. Defaults to same as label.

	 */
	function __construct($label, $value, $checked=false, $name=null, $id=null){
		$this->setTagName("input");
		$this->setAttribute("type", "checkbox");
		$this->checked = $checked;
		parent::__construct($label, $value, $name, $id);
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
	 */
	public function setChecked($checked){	//paramater checked is a boolean inidcating whether button is checked
		$this->checked = $checked;
	}
	
	public function render(){
		return $this->label->render() . parent::render() . "\n";
	}
}
?>
