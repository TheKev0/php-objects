<?php
	//This script creates a bunch of form input fields and serializes them into a file. Another script will unserialize and use it.
	
	//Get php object library
	ini_set("display_errors", "1");
	require_once("../com/com.inc.php");
	
	$inputs = array();
	$inputs[] = new Text("First Name", "");
	$inputs[] = new Text("Last Name", "");
	$inputs[] = new Text("Middle Initial", "");
	$inputs[] = new Text("Street Address", "");
	$inputs[] = new Text("City", "");
	$inputs[] = new Select("State", array("", "CA", "NV", "AZ", "..."));
	
	//serialize to a file
	$status = file_put_contents("savedForm.txt", serialize($inputs));
	
	echo "Serialization: $status";
	
?>
