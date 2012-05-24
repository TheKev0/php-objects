<?php
/**
 * Encapsulates xml or html tag. The constructor assumes a non-inline element 
 * (table instead of img tags) This can be set explicityly using 
 * setIsInline(). InnerHTML cannot be set after an Element has been set to be inline. This
 * class also stores child nodes and has simple functions to add/remove/iterate through 
 * them. This class serves as a base class for any object that represents an HTML tag.
 * @package com
 * @author Kevork Sepetci 
 * @todo throw exception if trying to set innerHTML when tag is inline.
 * @todo add xpath (see SimpleXMLElement)
 * @todo customizeable settings for markup (tag names all caps, )
 * @todo add static method to generate Element from SimpleXMLElement
 * @todo warn if id is set through setAttribute()
 * @todo have render indent properly. (right # of tabs)
 */
class Element implements Iterator, ArrayAccess{
	
	/**
	 * The tag name of this Element (HTML ex: table for <table></table>)
	 */
	protected $tagName = "";

	/**
	 * The inner content of this Element.
	 * @todo Add support for children Element objects
	 * @todo Change name to something general
	 */
	protected $innerHTML = "";
	
	/**
	 * An associative array of attributes for this Element.
	 */
	protected $attributes = array();
	
	/**
	 * Indicates whether this tag is inline.
	 */
	protected $isInline = false;
	
	/**
	 * Holds the child Element objects of this element Object
	 */
	protected $children = array();
	
	private $position = 0;
	
	/**
	 * Construct an Element object.
	 * @param string $tagName The tag name of this tag. Cannot be an empty string
	 * @param string $innerHTML Optional: The inner HTML of this Element
	 * @param array $attributes Optional: an associative array of attributes for this Element
	 */
	public function __construct($tagName, $innerHTML = "", $attributes = array()){
		$this->tagName = $tagName;
		$this->innerHTML = $innerHTML;
		$this->attributes = $attributes;
	}
	
	/**
	 * Set the tagName for this Element.
	 * @param string $tagName The tag name of this Element
	 * @todo throw IllegalArgumentException if tagName is empty
	 */
	protected function setTagName($tagName){
		$this->tagName = $tagName;
	}
	
	/**
	 * Get the tagname of this Element
	 * @return string the tagname of this Element
	 */
	public function getTagName(){
		return $this->tagName;
	}
	
	/**
	 * Get the inner HTML of this Element.
	 * @return string the inner HTML of this Element
	 */
	public function getInnerHTML(){
		return $this->innerHTML;
	}
	
	/**
	 * Set the inner HTML of this Element.
	 * @param string $innerHTML the inner HTML of this Element
	 */
	public function setInnerHTML($innerHTML){
		$inner = $this->getInnerHTML();
		$this->innerHTML = $innerHTML;
		return $inner;
	}
	
	/**
	 * Get all attributes of this Element.
	 * @return array array of attributes for this Element
	 */
	public function attributes(){				//returns associative array of attributes or a string for input element
		return $this->attributes;
	}
	
	/**
	 * Get an attribute of this Element.
	 * @param string $name the name of the attribute
	 * @return string|boolean returns value of specified attribute or false if it is not present
	 */
	public function getAttribute($name){
		if(!array_key_exists($name, $this->attributes)){
			return false;
		}
		return $this->attributes[$name];
	}
	
	/**
	 * Adds an attribute to this Element. If given attribute is already defined new value is appended.
	 * @see setAttribute
	 * @param string $name name of new attribute
	 * @param string $value value of new attribute
	 * @return boolean Returns true if given attribute was already present, and false otherwise
	 */
	public function addAttribute($name, $value){
		if(array_key_exists($name, $this->attributes)){
			$this->attributes[$name] = $this->attributes[$name] . " " . $value;
			return true;
		}
		else{
			$this->setAttribute($name, $value);
			return false;
		}
	}
	
	/**
	 * Set attribute for this input field, overwriting the old one if it is present.
	 * @param string $name name of new attribute
	 * @param string $value value of new attribute
	 * @return boolean Returns true if given attribute was already present and was replaced, and false if it was not present.
	 */
	public function setAttribute($name, $value){
		$overwritten = array_key_exists($name, $this->attributes);
		$this->attributes[$name] = $value;
		return $overwritten;
	}
	
	/**
	 * Removes specified attribute of the Element.
	 * @param string $name name of new attribute to remove
	 * @return boolean Returns true if given attribute was present and removed, and false if it was not present.
	 */
	public function clearAttribute($name){
		$exists = array_key_exists($name, $this->attributes);
		unset($this->attributes[$name]);
		return $exists;
	}
	
	/**
	 * Indicates whether this tag is inline.
	 * @return boolean true if this is an inline tag and false otherwise
	 */
	public function getIsInline(){
		return $this->isInline;
	}
	
	/**
	 * Sets whether this tag is inline.
	 * @param boolean $isInline true makes element inline
	 * @return boolean true if this was an inline tag and false otherwise
	 */
	public function setIsInline($isInline){
		if(is_bool($isInline)){
			$old = $this->isInline;
			$this->isInline = $isInline;
			return $old;
		}
		return $old;
	}
	
	/**
	 * Append a child Element Object to this Element object, or as the $ith child where $i is given as the paramter
	 * @param Element $child the child Element to append
	 * @param int $index (Optional) The index to place the child element at (0 is first row after header). The element is placed at the end by default.
	 * @return int new number of child Elements
	 */
	public function appendChild(Element $child, $index=-1){
		if($index != -1){
			array_splice($this->children, $index, 0, array($child));
		}else{
			$this->children[] = $child;
		}
		return count($this->children);
	}
	
	/**
	 * Get a child Element of this Element object
	 * @param int $index the index of the child to return
	 * @return Element|boolean the child Element of this Element object at $index, or false if none exists.
	 */
	public function getChild($index){
		if(isset($this->children[$index])){
			return $this->children[$index];
		}
		return false;
	}
	
	/**
	 * Get all children of this Element object
	 * @return array An array of Element objects
	 */
	public function children(){
		return $this->children;
	}
	
	/**
	 * Remove a child Element of this Element object
	 * @param int $index the index of the child to remove
	 * @return Element|boolean the child removed Element of this Element object at $index, or false if none exists.
	 */
	public function removeChild($index){
		if(isset($this->children[$index])){
			$child = $this->children[$index];
			array_splice($this->children, $index, 1);
			return $child;
		}
		return false;
	}
	
	/**
	 * Return number of children of this Element.
	 * @return int number of children of this Element
	 */
	public function numChildren(){
		return count($this->children);
	}
	
	/**
	 * Magic method. Alias of render()
	 * @return string A string representation of this Element. ie. The XML
	 */
	public function __toString(){					//alias of toHTML
		return $this->render();
	}
	
	/**
	 * Prints the XML for this Element. Has alias __toString()
	 * @return string A string representation of this Element. ie. The XML
	 */
	public function render(){
		$html = "";
		if($this->getIsInline() === false){
			$html = "<{$this->tagName} ";
			$html .= $this->arrayToAttributesString($this->attributes);
			$html .= ">";
			if(!empty($this->innerHTML)){
				$html .= "{$this->innerHTML}\n";
			}
			foreach($this->children as $child){
				$html .= "\n\t" . $child;
			}
			$html .= "</{$this->tagName}>";
		}else{
			$html = "<{$this->tagName} ";
			$html .= $this->arrayToAttributesString($this->attributes);
			$html .= "/>";
		}
		return $html;
	}
	
	/**
	 * Turns an associative array into a string to be used as the attributes in an xml tag.
	 * @param array $array is an associative array holding the attributes of an html tag.
	 * @return string returns the String representation of the associative array.
	 */
	protected static function arrayToAttributesString($array){
		if(empty($array) || is_null($array)){
			return "";
		}
		$html = "";
		$keys = array_keys($array);
		foreach($keys as $key){		//add other attributes
			$value = $array[$key];
			$html .= $key . '= "'.$value.'" ';
		}
		return $html;
	}
	
/*
 * ArrayAccess methods here...
 */
	public function offsetExists($offset){
		return isset($this->children[$offset]);
	}
	
	public function offsetGet($offset){
		return $this->children[$offset];
	}
	
	public function offsetSet($offset, $value){
		if(!($value instanceof Element)){
			//throw exception
			return false;
		}
		$this->children[$offset] = $value;
	}
	
	public function offsetUnset($offset){
		unset($this->children[$offset]);
	}
 	
/*
 * Iterator methods here...
 */
	public function current(){
		return $this->children[$this->position];
	}
	
	public function key(){
		return $this->position;
	}
	
	public function next(){
		$this->position++;
	}
	
	public function rewind(){
		$this->position = 0;
	}
	
	public function valid(){
		return isset($this->children[$this->position]);
	}
}

?>
