<?php
 
//	header("Content-Type: text/plain");
 	ini_set("display_errors", 1);

 	
	require_once("../com/com.inc.php");
	
	//Form Example
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
	
	
	//Element DOM Navigation
	$div = new Element("div");
	$div->appendChild(new Element("img", "", "", array("src"=>"http://us2.php.net/images/php.gif")));
	$div->appendChild(new Element("img", "", "", array("src"=>"http://us2.php.net/images/php.gif")));
	$div->appendChild(new Element("img", "", "", array("src"=>"http://us2.php.net/images/php.gif")));
	foreach($div as $child){
		if($child->getTagName() == "img"){
			$child->setIsInline(true);
		}
	}
	//echo $div;


	//Table Example
	$table = new Table(5, array("Head1", "Head2", "Head3", "Head4", "Head5"));
	$table->addRowStrings(array("a1", "b1", "c1", "d1", "e1"));
	$table->addRowStrings(array("a2", "b2", "c2", "d2", "e2"));
	$table->addRowStrings(array("a3", "b3", "c3", "d3", "e3"));
	$table->addRowStrings(array("a4", "b4", "c4", "d4", "e4"));
	$table->addRowStrings("Spanning row", true);
	$table->addHeaderStrings("Part 1", true, 0);
	$table->addAttribute("border", "2");	
	$table->addHeaderStrings("Part 2", true, 4);
	
	
	
		
?>

<html>
	<head>
		<link rel="StyleSheet" href="style.css" type="text/css" media=screen>
	</head>
	
	<body>
		<?php
			echo $table . "<br />";
			echo $form;
		?>
	</body>
</html>
