<?php
/*	This script uses a template and must set the following variables which a template file (a php script) will use.
 *	$_title
 *	$_javascript
 *	$_css
 *	$_head
 *	$_content
 *	$_keywords
 *	$_descrioption
 */
 
//	header("Content-Type: text/plain");
 	ini_set("display_errors", 1);

 	
	require_once("../com/com.inc.php");
	
	
	$form = new Form();
	$form->addField(new Checkbox("Online Only", "Online Only", false));
	$form->addField(new File("Your file: ", ""));
	$form->addField(new Hidden("this is hidden", "name", "id"));
	$form->addField(new Radio("MPA", "MPA", false, "program"));
	$form->addField(new Radio("MSW", "MSW", false, "program"));
	$form->addField(new Radio("CDS", "CDS", false, "program"));
	$form->addField(new Radio("IEP", "IEP", false, "program"));
	$form->addField(new Text("Your text here please ", ""));
	$form->addField(new TextArea("Comments", ""));
	$form->addField(new Select("Pick your favorite color", array("", "Red", "Green", "Blue")));
	$form->addField(new Reset("Reset NOW", false, "program"));
	$form->addField(new Submit("Submit"));
	$form->addField(new Submit("Preview"));

	$form->loadSubmittedValues();
	
	
?>

<html>
	<head>
		<link rel="StyleSheet" href="style.css" type="text/css" media=screen>
	</head>
	
	<body>
		<?php echo $form; ?>
	</body>
</html>
