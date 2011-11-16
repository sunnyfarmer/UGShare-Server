<?php


/**
 * @author samson
 * @version 1.0
 * @created 01-一月-2011 10:08:29
 */
class Models_Datatype_PDTFloat
{	
	const ABIGGERTHANB = 1;
	const AEQUALTOB = 0;
	const ASMALLERTHANB = -1;
	
	var $digit;

	public function __construct($newDigit)
	{	
		$this->digit = $newDigit;
	}
	/**
	 * 
	 * 比较两个对象
	 * @param PPTFloat $digit1
	 * @param PPTFloat $digit2
	 */
	static function compare($digit1 , $digit2)
	{
		$returnCode = null;
		$d1 = $digit1->getdigit();
		$d2 = $digit2->getdigit();
		if ($d1 > $d2)
			$returnCode = self::ABIGGERTHANB;
		else if ($d1 == $d2)
			$returnCode = self::AEQUALTOB;
		else 
			$returnCode = self::ASMALLERTHANB;
		return $returnCode;
	}

	/**
	 * 测试是否大于某值
	 * 
	 * @param float digit
	 */
	function biggerThan($digit)
	{
		if ($this->digit > $digit)
			return true;
		else 
			return false;
	}
	/**
	 * 测试是否小于某值
	 * 
	 * @param float digit
	 */
	function smallerThan($digit)
	{
		if ($this->digit < $digit)
			return true;
		else 
			return false;
	}
	


	function getdigit()
	{
		return $this->digit;
	}

	/**
	 * 
	 * @param newVal
	 */
	function setdigit($newVal)
	{
		$this->digit = $newVal;
	}


}
?>