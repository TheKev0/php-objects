<?php
/**
 * Encapsulates a submit button in a form.
 * @package com.form.html
 */
class Submit extends AbstractInput{
	
	/**
	 * Constructor: Create an instance of a submit form button.
	 * @param string $value The value attribute of the input field
	 * @param string $name (Optional) The name attribute of the input field. Defaults to same as label.
	 * @param string $id (Optional) The id attribute of the input field. Defaults to same as label.
	 */
	public function __construct($value, $name=null, $id=null){
		$this->setIsInline(true);
		$this->setTagName("input");
		$this->setAttribute("type", "submit");
		$this->setPrintLabel(false);
		parent::__construct("", $value, $name, $id);
	}
	
}	
?>
