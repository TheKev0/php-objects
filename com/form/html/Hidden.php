<?php
/**
 * Encapsulates a hidden input field in a form.
 * @package Form
 */
class Hidden extends AbstractInput{	

	/**
	 * Constructs a Hidden object.
	 * @param string $name The name attribute of the hidden field
	 * @param string $val The value attribute of the hidden field
	 * @param array $validate_function An array with the first (0th) element being the name of a validation function, the second as the second parameter to the validation function and so on. The first parameter of the validation function should be the value being validated. This is provided automatically.
	 * @param array|string $attributes An associtive array of custom attributes for the text area, or string of attributes to be inserted into the tag.
	 */
	function __construct($name, $val, $validate_function=array(), $attributes=array()){
		$this->name = $name;
		$this->value = $val;
		parent::__construct("", $validate_function, $attributes);
	}
	
	/**
	 * Returns the HTML for the text field in its current state.
	 * Has alias @see __toString()
	 */
	public function toHTML(){
		$html = '<input type= "hidden" name= "'.$this->name.'" value= "'.$this->value.'" id= "'.$this->id.'" ';
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
