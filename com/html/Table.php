<?php
/**
 * This class encapsulates a simple html table. The number of columns is fixed at 
 * initialization but rows can be added and removed. There are methods to manipulate the 
 * optional main header at the top of the table. Header rows can also be added to the 
 * table body. Both normal rows and header rows in the body can be made to have a single 
 * cell that spans the length of the table. This is accomplished using the colspan 
 * attribute for HTML tables. Merged cells that don't span the entire length of the table 
 * are also possible but must be done using methods inherited from com.Element.
 *
 * This is a subclass of Element and is thus both iterable and accessible like an array.
 * Rows are stored as child 'tr' Element objects and cells are 'td' Element objects which
 * are subchildren of the 'tr' objects. Accessing the table like an array returns the 
 * Element object of the row or cell. Index 0 is the first row of the table excluding the
 * main header.
 * 
 * ex: <code>$rowElement = $table[4]; 		//returns a 'tr' Element object at row 5</code>
 * 
 * ex: <code>$cellElement = $table[4][3]		//returns a 'td' Element object at row 5 col 4</code>
 * 
 * ex: <code>$cellElement  = $table[4][3]->getInnerText();	//returns a string of the contents in row 5 col 4</code>
 * 
 * @author Kevork Sepetci
 * @package com.html
 * 
 * @todo addHeaderElement()
 * @todo addRowElement()
 * @todo add static method createFromMySQLResult();
 * @todo Exception Handling
 */
class Table extends HTMLElement{

	/**
	 * The number of columns in the table
	 */
	 protected $cols;
	 
	 /**
	 * The number of rows in the table
	 */
	 protected $rows = 0;
	 
	 /**
	 * The table header as a 'tr' Element object
	 */
	 protected $header = false;
	 
	 /**
	 * 2d array holding table rows. Excludes table header.
	 */
	 protected $table = array();
	 
	/**
	 * Construct a table object.
	 * @param int $cols The number of columns in the table
	 * @param array $header (Optional) Array of strings for main header. If not provided the table will have no main header row, unless set by accessor methods. The length of this array must match the cols parameter.
	 * @todo Throw IllegalArgumentException if header has wrong number of elements
	 */
	public function __construct($cols, $header=null){
		$this->cols = $cols;
		$this->tagName = "table";
		if($header != null){
			if(count($header) == $cols){
				$headerRow = new Element("tr");
				foreach($header as $headerString){
					$headerRow->appendChild(new Element("th", $headerString));
				}
				$this->header = $headerRow;
			}else{
				//todo throw IllegalArgumentException
			}
		}
	}
	
	/**
	 * Add a row to the body of the table.
	 * @param array $row An array of strings to go into each cell of the row. If the span parameter is set to true, this shuold a string.
	 * @param boolean $span (Optional) If true the row will have one cell that spans the length of the table. ($row must have one element)
	 * @param int $index (Optional) The index of the table where the row should be added. 0 is the first row of the table excluding the main header.
	 * @return int the new number of rows in the table
	 * @todo IllegalArgumentException when inconsistent arguments are given
	 */
	public function addRowStrings($row, $span=false, $index=-1){
		if((is_array($row) && count($row) != $this->cols) || ($span && !is_string($row))){
			//throw IllegalArgumentException
			return false;
		}
		$index = ($index == null) ? $this->rows : $index;
		$rowElement = new Element("tr");
		if($span === true){
			$rowElement->appendChild(new Element("td", $row, "", array("colspan"=>$this->cols)));
		}else{
			$rowKeys = array_keys($row);
			for($i = 0; $i < $this->cols; $i++){
				$cellContents = (isset($rowKeys[$i])) ? $row[$rowKeys[$i]] : "";
				$cell = new Element("td", $cellContents);
				$rowElement->appendChild($cell);
			}
		}
		$this->appendChild($rowElement, $index);
		return count($this->children);
	}
	
	/**
	 * Add a header row to the body of the table. This function does not set the main table header.
	 * @param array $row An array of strings to go into each cell of the header. If the span parameter is set to true, this shuold a string.
	 * @param boolean $span (Optional) If true the row will have one cell that spans the length of the table. ($row must have one element)
	 * @param int $index (Optional) The index of the table where the row should be added. 0 is the first row of the table excluding the main header.
	 * @return int the new number of rows in the table
	 * @todo IllegalArgumentException when inconsistent arguments are given
	 */
	public function addHeaderStrings($row, $span=false, $index=-1){
		if((is_array($row) && count($row) != $this->cols) || ($span && !is_string($row))){
			//throw IllegalArgumentException
			return false;
		}
		$index = ($index == null) ? $this->rows : $index;
		$rowElement = new Element("tr");
		if($span === true){
			$rowElement->appendChild(new Element("th", $row, "", array("colspan"=>$this->cols)));
		}else{
			$rowKeys = array_keys($row);
			for($i = 0; $i < $this->cols; $i++){
				$cellContents = (isset($rowKeys[$i])) ? $row[$rowKeys[$i]] : "";
				$cell = new Element("th", $cellContents);
				$rowElement->appendChild($cell);
			}
		}
		$this->appendChild($rowElement, $index);
		return count($this->children);
	}
	
	/**
	 * Set the main header row of the table. This function sets only the main table header at the top of the table.
	 * @param array $row An array of strings to go into each cell of the header. If the span parameter is set to true, this shuold a string.
	 * @param boolean $span (Optional) If true the row will have one cell that spans the length of the table. ($row must have one element)
	 * @todo IllegalArgumentException when inconsistent arguments are given
	 */
	public function setMainHeaderStrings($row, $span=false){
		if((is_array($row) && count($row) != $this->cols) || ($span && !is_string($row))){
			//throw IllegalArgumentException
			return false;
		}
		$rowElement = new Element("tr");
		if($span === true){
			$rowElement->appendChild(new Element("th", $row, "", array("colspan"=>$this->cols)));
		}else{
			$rowKeys = array_keys($row);
			for($i = 0; $i < $this->cols; $i++){
				$cellContents = (isset($rowKeys[$i])) ? $row[$rowKeys[$i]] : "";
				$cell = new Element("th", $cellContents);
				$rowElement->appendChild($cell);
			}
		}
		$this->header = $rowElement;
	}
		
	/*
	 * OLD VERSION
	 * Append a row to the table as an array of strings to go in each cell.
	 * @param array|string|Element $row An array of strings or td Element objects.
	 * @param boolean $span (Optional) If true the $row attribute can be a string or object and this row will have one cell that spans the length of the table.
	 * @param int $index (Optional) The index of the table where the row should be added. 0 is the first row after the header (If there is one).
	 * @todo IllegalArgumentException when inconsistent arguments are given
	 * @todo restrict to only array of strings and povide another method for tr Elements
	 */
	/*public function addRowContent($row, $span=false, $index=-1){
		$index = ($index == null) ? $this->rows : $index;
		$rowElement = new Element("tr");
		if($span === true && !($row instanceof Element)){
			$rowElement->appendChild(new Element("td", $row, "", array("colspan"=>$this->cols)));
		}else if($span !== true){
			$rowKeys = array_keys($row);
			for($i = 0; $i < $this->cols; $i++){
				$cellContents = (isset($rowKeys[$i])) ? $row[$rowKeys[$i]] : "";
				$cell = new Element("td", $cellContents);
				$rowElement->appendChild($cell);
			}
		}
		$this->appendChild($rowElement, $index);
	}
	*/
	
	/**
	 * ON HOLD UNTIL LATER. MAY NOT IMPLEMENT AS PUBLIC METHOD.
	 * Append a row as a "<tr>" Element object. The user is trusted with having the right number of "<td>" Element objects within .
	 * @param Element $rowElement a "<tr>" object to be appended to the table.
	 * @param int $index (Optional) The index of the table where the row should be added. 0 is the first row after the header (If there is one).
	 */
	/*protected function addRowElement(Element $rowElement, $index=null){
		
	}*/
	
	/**
	 * Returns the primary table header as a 'tr' Element object with nested 'td' Element objects
	 * @return Element|boolean a 'tr' Element object with the appropriate number of child 'td' Elements or false if no header exists for this table
	 */
	public function getHeaderElement(){
		return $this->header;
	}
	 
	/**
	* Returns the primary table header as an array of strings.
	* @return array|boolean an array of strings containing table headers or false if no header exists for this table
	*/
	public function getHeaderStrings(){
		$strings  = array();
		if($this->header !== false){
			foreach($this->header as $header){
				$strings[] = $header->getInnerText();
			}
			return $strings;
		}
		return false;
	}
	
	/**
	 * Returns the chosen row as a 'tr' Element object. Identical to $table[$index]
	 * @param int $index The index of the row to retrieve
	 * @return Element|boolean a 'tr' Element object with the appropriate number of child 'td' Elements or false if row doesn't exist
	 */
	public function getRowElement($index){
		if(isset($this->children[$index])){
			return $this->children[$index];
		}
		return false;
	}
	 
	/**
	* Returns the chosen row as an array of strings.
	* @return array|boolean an array of strings containing the contents of the table or false if no header exists for this table
	*/
	public function getRowStrings($index){
		if(isset($this->children[$index])){
			$strings = array();
			foreach($this->children[$index] as $child){
				$strings[] = $child->getInnerText();
			}
			return $strings;
		}
		return false;
	}
	
	/**
	 * Remove a row at index $index from the table.
	 * @param int $index The index of the row to remove
	 * @return Element|boolean the removed Element or false if offset $index doesn't exist
	 */
	public function removeRow($index){
		if(isset($this->children[$index])){
			$row = $this->children[$index];
			$this->removeChild($index);
			return $row;
		}
		return false;
	}
	
	
	/**
	 * Returns the HTML for this table. Has alias __toString()
	 * @return string A string representation of this Element. ie. The XML
	 */
	public function render(){
		if($this->header !== false){
			$this->appendChild($this->header, 0);
		}
		$string = parent::render();
		$this->removeChild(0);
		return $string;
	}
	
	/**
	 * This static method returns an instance of a Table object given a mysql result resource.
	 * @param resource $result A mysql result resource
	 * @param array $header (Optional) A string array containing the headers of the table. Defaults to the column names in the SQL SELECT clause in the same order.
	 * @param array $columns (Optional) A string array of the column names from the SQL SELECT clause to be printed. Order of the columns is relevant. Invalid column names are ignored. Defaults to all of the columns in the SQL SELECT clause in the same order.
	 * @param int $numRows (Optional) The maximum number of rows to print in the table. Defaults to printing all of the rows in the mysql result resource.
	 * @return Table a table object containing data from the MySQL result resource
	 * @todo throw IllegalArgumentException where necessary.
	 */
	public static function createFromMySQLResult($result, $header=null, $columns=null, $numRows=-1){
		if(!is_resource($result)){
			//throw IllegalArgumentException
			return false;
		}
		$numCols = mysql_num_fields($result);
		$table = new Table($numCols);
		$rowCount = 0;
		$numRows = ($numRows == -1) ? PHP_INT_MAX : $numRows;
		if($columns == null){	//Print all columns in order of select clause
			while(($row = mysql_fetch_assoc($result)) != false && $rowCount < $numRows){
				$table->addRowStrings($row);
				$rowCount++;
			}
			if($header == null){//Set default main header
				$header = array();
				for($i = 0; $i < $numCols; $i++){
					$header[] = mysql_fetch_field($result, $i)->name;
				}
			}
		}else{					//Print only desired columns in desired order
			while(($row = mysql_fetch_assoc($result)) != false && $rowCount < $numRows){
				$customRow = array();
				for($i = 0; $i < count($columns); $i++){
					if(isset($row[$columns[$i]])){
						$customRow[] = $row[$columns[$i]];
					}
				}
				$table->addRowStrings($customRow);
				$rowCount++;
			}
			if($header == null){//Set default main header
				$header = $columns;	
			}
		}
		$table->setMainHeaderStrings($header);
		return $table;
	}
}

?>
