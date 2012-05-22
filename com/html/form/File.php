<?php
/**
 * Encapsulates a file upload form input field.
 * @package com.html.form
 * @author Kevork Sepetci
 */
class File extends AbstractInput{	
	
	/**
	 * Constructor: Create an instance of a File object (File upload form input field).
 	 * @param string $labelString The label of the input field. Appears in <label> tags.
	 * @param string $value The value attribute of the input field
	 * @param string $name (Optional) The name attribute of the input field. Defaults to same as label.
	 * @param string $id (Optional) The id attribute of the input field. Defaults to same as label.
	 */
	function __construct($labelString, $value, $name=null, $id=null){
		$this->setIsInline(true);
		$this->setTagName("input");
		$this->setAttribute("type", "file");
		parent::__construct($labelString, $value, $name, $id);
	}

}
?>
