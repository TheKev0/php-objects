<?php

 	require_once("../com/com.inc.php");
	require_once("../../mysql.inc");
	
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

	//Table Example
	$table = new Table(5, array("Head1", "Head2", "Head3", "Head4", "Head5"));
	$table->addRowStrings(array("a1", "b1", "c1", "d1", ""));
	$table->addRowStrings(array("a2", "b2", "c2", "d2", "e2"));
	$table->addRowStrings(array("a3", "b3", "c3", "d3", "e3"));
	$table->addRowStrings(array("a4", "b4", "c4", "d4", "e4"));
	$table->addRowStrings("Spanning row", true);
	$table->addHeaderStrings("Part 1", true, 0);
	$table->addAttribute("border", "2");	
	$table->addHeaderStrings("Part 2", true, 4);
	
	//Table from MySQL Results
	$result = mysql_query("SELECT CONCAT(Dept, ' ', Number) AS Class, Instructor FROM SOLARClasses WHERE Dept = 'ECE' AND Term = 2125");
	$table2 = Table::createFromMySQLResult($result, null, null);
	$i = 0;
	foreach($table2 as $row){
		if($i % 2 == 0){
			$row->addAttribute("style", "background-color: #ddd;");
		}
		$i++;
	}
		
?>

<html>
	<head>
		<link rel="StyleSheet" href="style.css" type="text/css" media=screen>
	</head>
	
	<body>
		<?php
			echo $div . "<br />";
			echo $table . "<br />";
			echo $table2 . "<br />";
			echo $form;
		?>
	</body>
</html>
