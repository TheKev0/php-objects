<?php
/**
 * This file includes all of the files in the commons API in the right order.
 * Changing the order of include files may break things!!!
 * @package com
 * @author Kevork Sepetci
 */
 

	//Package: com
	require_once("Element.php");
	require_once("html/HTMLElement.php");

	require_once("html/form/AbstractInput.php");
	require_once("html/Form.php");
	
	
	require_once("html/form/Text.php");
	require_once("html/form/Select.php");
	require_once("html/form/TextArea.php");
	require_once("html/form/File.php");
	require_once("html/form/Radio.php");
	require_once("html/form/Checkbox.php");
	require_once("html/form/Submit.php");
	require_once("html/form/Reset.php");
	require_once("html/form/Hidden.php");
	

	require_once("html/Table.php");
