<?php
/**
 * Encapsulates a text box (<textarea>) in a form.
 * @package Form
 */
class TextArea extends AbstractInput{

	/**
	 * Constructs a Text object.
	 * @param string $name The name attribute of the text area field
	 * @param string $val The value attribute of the text area field
	 * @param string $label Optional: The label of the text area. NOTE: this will not be in the HTML generated by __toString or toHTML()
	 * @param array $validate_function An array with the first (0th) element being the name of a validation function, the second as the second parameter to the validation function and so on. The first parameter of the validation function should be the value being validated. This is provided automatically.
	 * @param array|string $attributes An associtive array of custom attributes for the text area, or string of attributes to be inserted into the tag.
	 */
	function __construct($name, $val, $label=null, $validate_function=array(), $attributes=array()){
		$this->value = $val;
		$this->name = $name;
		parent::__construct($label, $validate_function, $attributes);
	}
	
	/**
	 * Returns the HTML for the text field in its current state.
	 * Has alias @see __toString()
	 */
	public function toHTML(){	//returns the HTML for this form field
		$html = '<textarea name= "' . $this->name . '" id= "'.$this->id.'" ';
		if(is_array($this->attributes)){
			$html .= " " . $this->arrayToAttributesString($this->attributes) . " ";
		}
		else{
			$html .= " " . $this->attributes . " ";
		}
		$html .= '>';
		
		$html .= $this->value . "</textarea>";
		return $html . "\n";
	}

}

?>
