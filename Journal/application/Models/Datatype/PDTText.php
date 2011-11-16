<?php


/**
 * @author samson
 * @version 1.0
 * @created 01-一月-2011 10:08:30
 */
class Models_Datatype_PDTText
{
	const ALONGERTHANB = 1;
	const ASHORTERTHANB = -1;

	private $text;
	private $len;

	static function compare($text1 , $text2)
	{
		$returnCode = null;
		$len1 = strlen($text1);
		$len2 = strlen($text2);
		
		if ($len1>$len2) 
		{
			$returnCode = self::ALONGERTHANB;
		}
		else
			$returnCode = self::ASHORTERTHANB;
			
		return $returnCode;
	}
	
	function __construct($text)
	{
		$this->text = $text;
		$this->len = strlen($this->text);
	}

	/**
	 * 测试字节数是否大于某值
	 * 
	 * @param num
	 */
	function longerThan($num)
	{
		if ($this->len > $num)
			return true;
		else 
			return false;
	}
	
	/**
	 * 测试字节数是否小于某值
	 * 
	 * @param num
	 */
	function shorterThan($num)
	{
		if ($this->len < $num)
			return true;
		else 
			return false;
	}
	
	function gettext()
	{
		return $this->text;
	}

	/**
	 * 
	 * @param newVal
	 */
	function settext($newVal)
	{
		$this->text = $newVal;
		$this->len = strlen($this->text);
	}
	/**
	 * @return the $len
	 */
	public function getLen() {
		return $this->len;
	}

}
?>