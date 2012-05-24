<?php
/**
 * Encapsulates an html tag. The constructor assumes a non-inline element 
 * (a table tag is non-inline, an img tag is inline). This can be set explicityly using 
 * setIsInline(). InnerHTML cannot be set after an Element has been set to be inline. This
 * class also stores child nodes and has simple functions to add/remove/iterate through 
 * them. This class serves as a base class for any object that represents an HTML tag.
 * @package com.html
 * @author Kevork Sepetci 
 * @todo warn if id is set through setAttribute()
 * @todo warn if inline style is added with 
 */
class HTMLElement extends Element{
	
	/**
	 * The id attribute of this Element
	 */
	protected $id;
	
	/**
	 * Holds the inline styles of an input field. NOTE: this should probably be moved to a more abstract class. It is not in Element because Element encapsulates XML tags as well as HTML.
	 */
	protected $styleRules = array();
	
	/**
	 * Construct an HTMLElement object.
	 * @param string $tagName The tag name of this tag. Cannot be an empty string
	 * @param string $innerHTML Optional: The inner HTML of this Element
	 * @param string $id Optional: The id attribute of this Element
	 * @param array $attributes Optional: an associative array of attributes for this Element
	 */
	public function __construct($tagName, $innerHTML = "", $id = "", $attributes = array()){
		$this->id = $id;
		parent::__construct($tagName, $innerHTML, $attributes);
	}
	
	/**
	 * Get the id attribute of this Element.
	 * @return string id of the input field
	 */
	public function getID(){
		return $this->id;
	}
	
	/**
	 * Set the id attribute of this Element.
	 * @param string $id id attribute of Element
	 * @return the old id
	 */
	public function setID($id){
		$previous = $this->getID();
		$this->id = $id;
		$this->setAttribute("id", $id);
		return $previous;
	}
			
	/**
	 * Magic method. Alias of render()
	 * @return string A string representation of this Element. ie. The XML
	 */
	public function __toString(){					//alias of toHTML
		return $this->render();
	}
	
	/**
	 * Prints the HTML for this Element. Has alias __toString()
	 * @return string A string representation of this Element. ie. The HTML
	 */
	public function render(){
		if(!empty($this->id)){
			$this->setAttribute("id", $this->id);
		}
		if(!empty($this->styleRules)){
			$this->addAttribute("style", $this->makeCSS());
		}
		return parent::render();
	}
	
	/**
	 * Adds an inline style to this Abstract input object. If the style rule was already present it is overriden with the new one.
	 * @param string $rule The CSS attribute (ex: background-color)
	 * @param string $value The value of the CSS rule
	 * @return the previous value of the given CSS rule, or false if none was set.
	 */
	public function setStyleRule($rule, $value){
		$oldRule = (isset($this->styleRules[$rule])) ? $this->styleRules[$rule] : false;
		$this->styleRules[$rule] = $value;
		return $oldRule;
	}
	
	/**
	 * Get value of CSS style rule
	 * @param string $rule The CSS attribute (ex: background-color)
	 * @return the value of the style rule or null if it doesn't exist
	 */
	public function getStyleRule($rule){
		return $this->styleRules[$rule];
	}
	
	/**
	 * Get all inline css styles.
	 * @return Associative array of inline CSS styles for this input object.
	 */
	public function getStyleRules(){
		return $this->styleRules;
	}
	
	/**
	 * Remove inline style rule
	 * @param string $rule the style rule to remove. (the part before the colon)
	 * @return the value of the removed style rule is returned or false if none was present
	 */
	 public function removeStyleRule($rule){
	 	$previous = $this->styleRules[$rule];
	 	if($previous !== false){
	 		unset($this->styleRules[$rule]);
	 		return $previous;
	 	}
	 	return false;
	 }
	 
	 /**
	 * Remove inline all inline styles.
	 * @return array an associative array of all the removed CSS rules. The key is the rule (part before the colon in CSS), and the value is the value (part after the colon in CSS).
	 */
	 public function clearStyles(){
	 	$previous = $this->styleRules;
	 	unset($this->styleRules);
	 	return $previous;
	 }
	 
	 /**
	  * Allows insertion of inline css rules as a string instead of adding them one by one with setStyleRule(). CSS rules that already exist are overriden with the ones provided.
	  * The string of CSS is parsed and stored internally so that individual rules can be overriden manually with a call to setStyleRule().
	  * @param string $css a string of valid css rules. (with rules ending in semicolons. ex: border-color: #ccc;)
	  */
	public function addStyleString($css){
		if(!empty($css)){
			$lines = explode(";", $css);
			foreach($lines as $line){
				@list($rule, $value) = explode(":", $line);
				if(isset($rule) && isset($value)){
					$rule = trim($rule);
					$value = trim($value);
					$this->setStyleRule($rule, $value);
				}
			}
		}
	}
	
	/**
	  * Internal function to print the CSS that goes inside the style attribute of an HTML tag.
	  * @return string a string containing the CSS rules.
	  */
	 protected function makeCSS(){
	 	$css = "";
	 	foreach($this->styleRules as $rule => $value){
	 		$css .= "$rule: $value; ";
	 	}
	 	return $css;
	 }
	
}

?>
