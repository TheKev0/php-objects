<?php
/**
 * This file includes all of the files in the commons API in the right order.
 * Changing the order of include files may break things!!!
 * @package com
 * @author Kevork Sepetci
 */
 

	//Package: com
	require_once("Element.php");

	require_once("form/AbstractInput.php");
	require_once("form/Form.php");
	
	
	require_once("form/html/Text.php");
	require_once("form/html/Select.php");
	require_once("form/html/TextArea.php");
	require_once("form/html/File.php");
	require_once("form/html/Radio.php");
	require_once("form/html/Checkbox.php");
	require_once("form/html/Submit.php");
	require_once("form/html/Reset.php");
	require_once("form/html/Hidden.php");
	

	require_once("html/Table.php");
