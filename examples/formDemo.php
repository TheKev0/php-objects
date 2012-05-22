<?php
	/*
		This script demonstrates how to use the form objects to construct a simple form 
		and serialize it for persistence. Note that there is more than one way to do this.
			1) The form is initialized, input fields are added.
			2) The submitted values are collected from the form.
			3) Submitted values are validated manually
			4) The form html is printed.
	*/
	
	//DEBUG
//	header("Content-Type: text/plain");
	ini_set("display_errors", "1");
	
	//include the stuff
 	require_once("../com/com.inc.php");
	
	//Initialize form
	$form = new Form();
	$form->addField(new Checkbox("Online Only", "Online Only", false));
	$form->addField(new File("Your file: ", ""));
	$form->addField(new Hidden("this is hidden", "name", "id"));
	$form->addField(new Radio("MPA", "MPA", false, "program"));	//name ('program')must match for radio buttons
	$form->addField(new Radio("MSW", "MSW", false, "program"));
	$form->addField(new Radio("CDS", "CDS", false, "program"));
	$form->addField(new Radio("IEP", "IEP", false, "program"));
	$form->addField(new Text("Your text here please ", ""));
	$form->addField(new TextArea("Comments", ""));
	$form->addField(new Select("Pick your favorite color", array("", "Red", "Green", "Blue")));
	$form->addField(new Reset("Reset NOW", false, "program"));
	$form->addField(new Submit("Submit"));
	$form->addField(new Submit("Preview"));
	
	//load submitted values
	$form->loadSubmittedValues();


?>

<html>
	<head>
		<link rel="StyleSheet" href="style.css" type="text/css" media=screen>
	</head>
	
	<body>
		<h1>Example Form</h1>
		<?php
			echo $form;
		?>
	</body>
</html>
