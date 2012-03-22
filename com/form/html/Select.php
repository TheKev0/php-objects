<?php
/**
 * Encapsulates an HTML select field.
 * @package com.form.html
 * @todo clearOptions() function
 * @todo clearOption() function
 * @todo options():Element[] function
 * @todo setUnselected function
 * @todo clearSelected function
 */
class Select extends AbstractInput{	

	/**
	 * Array of indexes of selected options in options array.
	 */
	private $selected = array();
	
	/**
	 * Array of option objects.
	 */
	private $options = array();
	
	/**
	 * Constructor: Create an instance of a select form input field.
 	 * @param string $labelString The label of the input field. Appears in <label> tags.
	 * @param Option[]|array $option An array of strings to be used as the options. If array is associative the keys are used as the value attribute of each option.
	 * @param string $name (Optional) The name attribute of the input field. Defaults to same as label.
	 * @param string $id (Optional) The id attribute of the input field. Defaults to same as label.
	 */
	public function __construct($labelString, $options, $name=null, $id=null){
		$this->setIsInline(false);
		$this->setTagName("select");
		$this->setOptions($options);
		parent::__construct($labelString, "", $name, $id);
	}
	
	/**
	 * Get selected option(s).
	 * @return string|boolean|array selected option(s). array of strings of selected options, or false if nothing is selected. 
	 * @example 
	 */
	public function getSelected(){
		if(count($selected) == 0){
			return false;
		}elseif(count($selected) == 1){
			return $selected[0];
		}else{
			$options = array();
			foreach($this->selected as $key){
				$options[] = $this->options[$key];
			}
			return $options;
		}
	}
	
	/**
	 * Set the default selected option(s). Calling this function clears all previously set selected entries.
	 * @param array|string $selected The selected option(s) or an array of selected options.
	 * @return string|boolean|array previously selected option(s). array of strings of selected options, or false if nothing is selected. Identical to value returned by getSelected() just before the call to this method.
	 */
	public function setSelected($selected){
		$previous = $this->getSelected();
		$this->selected = array_splice($this->selected, 0);

		if(is_string($selected)){
			$this->selected[] = $selected;
		}elseif(is_array($selected)){
			$this->selected = $selected;
		}
		
		return $previous;
	}
	
	/**
	 * Get the Element object encapsulating the option
	 * @param string $display the human readable string the option displays or a key defined by the associative array passed to setOptions or the constructor.
	 * @return array Associative array of options in this select field with the keys as the value attributes.
	 */
	public function getOptionElement($option){
		return $this->options[$option];
	}
	
	/**
	 * Set the options for this select field. Options are added to ones already present.
	 * @param array $options An array of strings to be used as the options. If array is associative the keys are used as the value attribute of each option.
	 */
	public function setOptions($options){
		if(is_array($options)){
			foreach($options as $key => $optionText){
				$opt = new Element("option");
				$display = (is_numeric($key)) ? $optionText : $key;
				$opt->setAttribute("value", $display);
				$opt->setInnerHTML($optionText);
				$this->options[] = $opt;
			}
		}
	}
	
	/**
	 * Adds an option to the select field.
	 * @param string $label displayed to user in select field
	 * @param string $value (Optional) value attribute of option tag
	 */
	public function addOption($label, $value=""){
		$option = new Element("option");
		$option->setAttribute("value", $value);
		$option->setInnerHTML($label);
		$this->options[] = $option;
	}
	
	/**
	 * Prints the markup for this input. Has alias __toString()
	 * @return string A string representation of this input. ie. The XML
	 */
	public function render(){
		$this->clearAttribute("value");
		$inner = "\n";
		foreach($this->options as $option){
			$inner .= "\t" . $option->render() . "\n";
		}
		$this->setInnerHTML($inner);
		return parent::render();
	}
}
