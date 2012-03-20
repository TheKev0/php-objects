<?php
/**
 * Encapsulates a reset button for a form.
 * @package Form
 */
class Reset extends AbstractInput{
	
	/**
	 * Constructor: Create an instance of a Radio button object.
	 * @param string $name The name of the radio button input (_POST key)
	 * @param string $val The value to submit when radio button is checked. (_Post value)
	 * @param array|string $attributes Optional: An associative array of html attributes or a string of attributes for the input tag
	 */
	function __construct($name, $val, $attributes=array()){
		$this->value = $val;
		$this->name = $name;
		$this->id = $this->name;
		$this->attributes = $attributes;
	}
	
	/**
	 * Returns the HTML for this input object.
	 * @see __toString()
	 */
	public function toHTML(){			//returns the HTML for this form field
		$html = '<input type= "reset" name= "' . $this->name . '" value= "' . $this->value . '" id= "'.$this->id.'"';
		
		if(is_array($this->attributes)){
			$html .= " " . $this->arrayToAttributesString($this->attributes) . " ";
		}
		else{
			$html .= " " . $this->attributes . " ";
		}
		$html .= '/>';
		return $html . "\n";
	}
}	
?>
