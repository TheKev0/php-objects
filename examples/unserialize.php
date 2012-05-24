<?php
	//This script unserializes some form input fields read in from a file, customizes it further, and prints it to screen.
	
	
	//Get php object library
	ini_set("display_errors", "1");
	require_once("../com/com.inc.php");
	
	//Get stored input fields
	$inputs = unserialize(file_get_contents("savedForm.txt"));
	
	//Put it together
	$form = new Form();
	foreach($inputs as $input){
		$form->addField($input);
	}
	$form->addField(new Submit("Submit"));
	
	//Load submitted values back into the form and save them for later
	//WARNING: Submitted values are never sanitized automatically
	$form->loadSubmittedValues();
	$submittedValues = $form->getSubmittedValues();
	
	//Do some really simple validation if the form was submitted
	if($submittedValues !== false){
		$haveErrors = false;
		foreach($submittedValues as $name => &$value){
			$value = htmlentities($value);
			if(empty($value)){
				$form[$name]->getLabelElement()->setStyleRule("color", "#f40808");	//make labels red
				$form[$name]->getLabelElement()->setStyleRule("font-weight", "bold");
				$form[$name]->addStyleString("border: 2px inset #f40808; background-color: #ffefa4;");
				$haveErrors = true;
			}
		}
	}
	
	//Print submitted values if the form was submitted
	if($submittedValues !== false && $haveErrors == false){
		$values = "";
		foreach($submittedValues as $name => $value){
			$values .= "<strong>$name:</strong> $value<br />";
		}
	}else{
		$values = "Looks like you left some stuff blank...";
	}
	
?>

<html>
	<head>
	</head>
	
	<body>
		<?php			
			echo "<h2>Plain old whatever style</h2>";
			echo $form->render(false);
			
			
			echo "<h2>Kev style (default)</h2>";
			echo $form;		//equivalent to $form->render();
			
			echo "<h2>Custom Formatting</h2>";
			echo <<<END
				<form method="{$form->getMethod()}" action="{$form->getAction()}" >
					 {$form["First Name"]->getLabelElement()}	{$form["First Name"]}, &nbsp 
					 {$form["Last Name"]->getLabelElement()}	{$form["Last Name"]}
					 {$form["Middle Initial"]->getLabelElement()}	{$form["Middle Initial"]} <br />
					 <p>Maybe some stuff here...if you want</p>
					{$form["Street Address"]->getLabelElement()}	{$form["Street Address"]} <br />
					{$form["City"]->getLabelElement()}	{$form["City"]}, &nbsp {$form["State"]} <br />
				</form>
			
END;
			
			echo "<br /><br /><br />";
			echo "<h2>Submitted Values</h2>";
			echo $values;
		?>
	</body>
</html>
