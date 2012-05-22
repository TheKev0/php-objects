<?php
/**
 * Encapsulates a reset button for a form.
 * @package com.html.form
 * @author Kevork Sepetci
 */
class Reset extends AbstractInput{
	
	/**
	 * Constructor: Create an instance of a reset form button.
	 * @param string $value The value attribute of the input field
	 * @param string $name (Optional) The name attribute of the input field. Defaults to same as label.
	 * @param string $id (Optional) The id attribute of the input field. Defaults to same as label.
	 */
	public function __construct($value, $name=null, $id=null){
		$this->setIsInline(true);
		$this->setTagName("input");
		$this->setAttribute("type", "reset");
		$this->setPrintLabel(false);
		parent::__construct("", $value, $name, $id);
	}
	
}	
?>
