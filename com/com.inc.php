<?php
/**
 * This file includes all of the files in the commons API in the right order.
 * Changing the order of include files may break things.
<<<<<<< HEAD
 * @package com.form
=======
 * @package com
>>>>>>> 88b6bb02c8b897a9a24c7fa9ca924d271bc29d4e
 */
 
	//Package: com
	require_once("Element.php");
<<<<<<< HEAD
	require_once("InlineElement.php");

	//Package: com.form
	require_once("form/AbstractInput.php");
	require_once("form/Form.php");
=======

	//Package: com.form
	require_once("form/AbstractInput.php");
>>>>>>> 88b6bb02c8b897a9a24c7fa9ca924d271bc29d4e
	
	//Package: com.form.html
	require_once("form/html/Text.php");
	require_once("form/html/Select.php");
	require_once("form/html/TextArea.php");
	require_once("form/html/File.php");
	require_once("form/html/Radio.php");
	require_once("form/html/Checkbox.php");
	require_once("form/html/Submit.php");
	require_once("form/html/Reset.php");
	require_once("form/html/Hidden.php");