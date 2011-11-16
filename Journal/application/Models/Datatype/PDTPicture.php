<?php


/**
 * @author samson
 * @version 1.0
 * @created 01-一月-2011 10:08:29
 */
class Models_Datatype_PDTPicture
{

	/**
	 * 如jpg、png
	 */
	var $format;
	var $length;
	var $title;
	var $width;

	function __construct()
	{
	}



	/**
	 * 如jpg、png
	 */
	function getformat()
	{
		return $this->format;
	}

	function getlength()
	{
		return $this->length;
	}

	function gettitle()
	{
		return $this->title;
	}

	function getwidth()
	{
		return $this->width;
	}

	/**
	 * 如jpg、png
	 * 
	 * @param newVal
	 */
	function setformat($newVal)
	{
		$this->format = $newVal;
	}

	/**
	 * 
	 * @param newVal
	 */
	function setlength($newVal)
	{
		$this->length = $newVal;
	}

	/**
	 * 
	 * @param newVal
	 */
	function settitle($newVal)
	{
		$this->title = $newVal;
	}

	/**
	 * 
	 * @param newVal
	 */
	function setwidth($newVal)
	{
		$this->width = $newVal;
	}

}
?>