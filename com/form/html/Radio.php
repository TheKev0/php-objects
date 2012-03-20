<?php
/**
 * Encapsulates a Radio buttom form input field.
 * @package Form
 */
class Radio extends AbstractInput{
	
	/**
	 * boolean Indicates whether this radio button is selected by default.
	 */
	private $checked;
	
	/**
	 * Constructor: Create an instance of a Radio button object.
	 * @param string $name The name of the radio button input (_POST key)
	 * @param string $val The value to submit when radio button is checked. (_Post value)
	 * @param boolean $checked Default state of radio button (true = checked)
	 * @param string $label Optional: The HTML label of the input field
	 * @param array $validate_function Optional: An array of callback functions that should be called to validate the value variable
	 * @param array|string $attributes Optional: An associative array of html attributes or a string of attributes for the input tag
	 */
	function __construct($name, $val, $checked, $label=null, $validate_function=array(), $attributes=array()){
		$this->value = $val;
		$this->name = $name;
		$this->checked = $checked;
		parent::__construct($label, $validate_function, $attributes);
	}
	
	/**
	 * Check if this radio button instance is selcted off by default.
	 * @return boolean true if this radio button is selected, false otherwise.
	 */
	public function isChecked(){
		return $this->checked;
	}
	
	/**
	 * Set this radio button as checked or unchecked.
	 * @param boolean $checked true to make radio button selected, false otherwise. NOTE: Making this radio button selected does NOT automatically deselect other radio buttons. This MUST be handled externally.
	 */
	public function setChecked($checked){
		$this->checked = $checked;
	}
	
	/**
	 * Returns the HTML for this input object.
	 * @see __toString()
	 */
	public function toHTML(){
		$html = '<input type= "radio" name= "'.$this->name.'" value= "'.$this->value.'" id= "'.$this->id.'" ';
		if($this->checked === true){
			$html .= 'checked= "checked" ';
		}
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
