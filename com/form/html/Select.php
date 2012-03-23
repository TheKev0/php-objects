<?php
/**
 * Encapsulates an HTML select field.
 * @package com.form.html
 * @todo clearOptions($values[]) function
 * @todo clearOption($value) function
 * @todo options():Element[] function
 * @todo addSelected(value) function
 * @todo clearSelected() function
 */
class Select extends AbstractInput{	

	/**
	 * Array of indexes of selected options in options array.
	 */
	private $selected = array();
	
	/**
	 * Array of option Elements.
	 */
	private $options = array();
	
	/**
	 * Constructor: Create an instance of a select form input field.
 	 * @param string $labelString The label of the input field. Appears in <label> tags.
	 * @param Option[]|array $option An array of strings to be used as the options. If array is associative the keys are used as the value attribute of each option. Otherwise the value attributes of the options will be the same as the option strings.
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
	 * Get selected option(s) values. If no value was explicitly defined in setOptions(), addOption(), or the constructor just use the option string. The string(s) returned by this method can be used in getOptionElement($value) to retrieve the option Element.
	 * @return string|boolean|array selected option(s). the value attribute of the selected option, or an array of values of selected options, or false if nothing is selected. 
	 * @example 
	 */
	public function getSelected(){
		if(count($this->selected) == 0){
			return false;
		}elseif(count($this->selected) == 1){
			return $this->selected[0];
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
	 * @param array|string $selected The value attribute of selected option(s) or an array of value attributes of selected options. If no value was explicitly defined in setOptions(), addOption(), or the constructor just use the option string
	 * @return string|boolean|array previously selected option(s). value attribute of selected option, array of value attributes of selected options, or false if nothing is selected.
	 */
	public function setSelected($value){
		$previous = $this->getSelected();
		if(count($previous) == 1){
			$previous = $previous[0];
		}

		$this->selected = array();
		if(is_string($value)){
			$this->selected[] = $value;
		}elseif(is_array($selected)){
			$this->selected = $value;
		}
		
		return $previous;
	}
	
	/**
	 * Get an option element by the value attribute. If no value was explicitly defined in setOptions(), addOption(), or the constructor just use the option string.
	 * @param string $display the human readable string the option displays or a key defined by the associative array passed to setOptions or the constructor.
	 * @return array Associative array of options in this select field with the keys as the value attributes.
	 */
	public function getOptionElement($value){
		return $this->options[$value];
	}
	
	/**
	 * Set the options for this select field. Options are added to ones already present.
	 * @param array $options An array of strings to be used as the options. If array is associative the keys are used as the value attribute of each option. If the array is not associative the values will be the same as the option string.
	 */
	public function setOptions($options){
		if(is_array($options)){
			foreach($options as $key => $optionText){
				$opt = new Element("option");
				$value = (is_numeric($key)) ? $optionText : $key;
				$opt->setAttribute("value", $value);
				$opt->setInnerHTML($optionText);
				$this->options[$value] = $opt;
			}
		}
	}
	
	/**
	 * Adds an option to the select field.
	 * @param string $label displayed to user in select field
	 * @param string $value (Optional) value attribute of option tag. Defaults to same as option string
	 */
	public function addOption($label, $value=""){
		$option = new Element("option");
		$value = (empty($value)) ? $label : $value;
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
		foreach($this->options as $value => $option){
			if(in_array($value, $this->selected)){
				$option->addAttribute("selected", "selected");
			}
			$inner .= "\t" . $option->render() . "\n";
		}
		$this->setInnerHTML($inner);
		return parent::render();
	}
}
