<?php
/**
 * This file contains two class definitions: Select and Option. The option object represents an option in a select field.
 */

/**
 * Encapsulates an HTML select field.
 */
class Select extends AbstractInput{	

	private $selected;
	private $options = array();
	
	/**
	 * Constructor: Create an instance of a Radio button object.
	 * @see Option
	 * @param string $name The name of the radio button input (_POST key)
	 * @param array $options An array of Option objects for this select field
	 * @param mixed $selected The index of the selected option from the $options parameter, or an array of indexes of the selected options from the $options parameter.
	 * @param string $label Optional: The HTML label of the input field
	 * @param array $validate_function Optional: An array of callback functions that should be called to validate the value variable
	 * @param array|string $attributes Optional: An associative array of html attributes or a string of attributes for the input tag
	 */
	function __construct($name, $options, $selected, $label=null, $validate_function=array(), $attributes=array()){
		$this->name = $name;
		$this->options = $options;
		$this->selected = $selected;
		if(is_array($selected)){
			foreach($selected as $selected_option){		//setting selected options
				$options[$selected_option]->setSelected(true);
			}
		}
		else if(isset($options[$selected])){
			$options[$selected]->setSelected(true);
		}
		parent::__construct($label, $validate_function, $attributes);
		if(!is_array($selected)){
			$this->value = $selected;
		}
	}
	
	/**
	 * @return array Array of default selected options
	 */
	public function getSelected(){
		return $this->selected;
	}
	
	/**
	 * Set the default selected options
	 * @param array $selected The selected options as an array of indexes of the options paramater
	 */
	public function setSelected($selected){
		$this->selected = $selected;
		
		//unselect everything else
		foreach($this->options as $option){
			$option->setSelected(false);
		}
		
		if(is_array($selected)){
			foreach($selected as $selected_option){		//setting selected options
				if($this->options[$selected_option] != null){
					$this->options[$selected_option]->setSelected(true);
				}
			}
		}
		else if($this->options[$selected] != null){
			$this->options[$selected]->setSelected(true);
		}
	}
	
	/**
	 * @return array Array of Option objects for this select field
	 */
	public function getOptions(){
		return $this->options;
	}
	
	/**
	 * Set the options for this select field
	 * @param array $options An array of Option objects to be used as the options for this select field
	 */
	public function setOptions($options){
		$this->options = $options;
	}
	
	/**
	 * Returns the HTML for this input object.
	 * @see __toString()
	 */
	public function toHTML(){
		$html = '<select name= "'.$this->name.'" id= "'.$this->id.'" ';
		if(count($this->selected) > 1){
			$html .= " multiple= \"true\"";
		}
		if(is_array($this->attributes)){
			$html .= " " . $this->arrayToAttributesString($this->attributes) . " ";
		}
		else{
			$html .= " " . $this->attributes . " ";
		}
		$html .= ">" . "\n";
		
		foreach($this->options as $option){	//add options
			$html .= $option->toHTML();
		}
		$html .= '</select>';
		return $html . "\n";
	}
	
}

/**
 * This class encapsulates one option in a Select drop-down. It is meant to be used by instances of Select.
 */
class Option extends AbstractInput{
	
	/**
	 * String to be used for the option. IE. the 'innerHTML' of an <option> tag. ex: <option>"display"</option>
	 */
	private $display;
	
	/**
	 * boolean value indicating that this option should be selected by default. <option selected= "selected"></option>
	 */
	private $isSelected;
	
	/**
	 * Construct an Option object.
	 * @param string $val The value attribute of the option tag
	 * @param string $display Optional: the 'innerHTML' of the option tag (<option>$display</option>). Defaults to same as value if not provided.
	 * @param boolean $isSelected Optional: boolean indicating if this option should be selected by default. (true: <option selected= "selected"></option>)
	*/
	function __construct($val, $display='', $isSelected=false){
		if($display == ''){
			$display = $val;
		}
		$this->value = $val;
		$this->display = $display;
		$this->isSelected = $isSelected;
	}
	
	/**
	 * @return string 'innerHTML' of option tag.
	 */
	public function getDisplay(){
		return $this->display;
	}
	
	/**
	 * Set the "innerHTML" of the option tag.
	 * @param string String representing display of option tag
	 */
	public function setDisplay($disp){
		$this->display = $disp;
	}
	
	/**
	 * Set the option as selected or not selected.
	 * @param boolean If true Option is selected, false otherwise.
	 */
	public function setSelected($sel){
		$this->isSelected = $sel;
	}
	
	/**
	 * @return boolean true if option is selected, false otherwise.
	 */
	public function isSelected(){
		return $this->isSelected;
	}
	
	/**
	 * Returns the HTML for the option in its current state.
	 * Has alias @see __toString()
	 */
	public function toHTML(){
		$html = '<option value= "'.$this->value.'" ';
		if($this->isSelected === true){
			$html .= 'selected= "selected"';
		}
		if(is_array($this->attributes)){
			$html .= " " . $this->arrayToAttributesString($this->attributes) . " ";
		}
		else{
			$html .= " " . $this->attributes . " ";
		}
		$html .= '>' . $this->display . '</option>' . "\n";
		return $html;
	}
}
?>
