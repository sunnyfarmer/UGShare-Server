<?php


/**
 * @author samson
 * @version 1.0
 * @created 01-一月-2011 10:08:30
 */
class Models_Datatype_PDTVideo
{

	var $length;
	var $title;
	var $url;

	function __construct()
	{
	}



	function getlength()
	{
		return $this->length;
	}

	function gettitle()
	{
		return $this->title;
	}

	function geturl()
	{
		return $this->url;
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
	function seturl($newVal)
	{
		$this->url = $newVal;
	}

}
?>