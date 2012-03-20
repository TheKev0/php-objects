<?php
/**
 * Encapsulates a submit button in a form.
 * @package Form
 */
class Submit extends AbstractInput{
	
	/**
	 * Constructs a Submit object. id attribute is same as the name attribute
	 * @param string $name The name attribute of the submit button
	 * @param string $val The value attribute of the button.
	 * @param array|string $attributes An associtive array of custom attributes for the submit button, or string of attributes to be inserted into the tag.
	 */
	function __construct($name, $val, $attributes=array()){
		$this->value = $val;
		$this->name = $name;
		$this->id = $name;
		$this->attributes = $attributes;
	}
	
	/**
	 * Returns the HTML for the submit button in its current state.
	 * Has alias @see __toString()
	 */
	public function toHTML(){
		$html = '<input type= "submit" name= "' . $this->name . '" value= "' . $this->value . '" id= "'.$this->id.'" ';
		
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
