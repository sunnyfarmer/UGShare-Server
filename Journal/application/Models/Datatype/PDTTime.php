<?php


/**
 * @author samson
 * @version 1.0
 * @created 01-一月-2011 10:08:30
 */
class Models_Datatype_PDTTime
{
	const AEARLIERTHANB = -1;
	const AEQUALTOB = 0;
	const ALATERTANB = 1;
	
	/**
	 * 
	 * milisecond	毫秒时间
	 * @var long
	 */
	var $time;

	static function compare($t1 , $t2)
	{
		$returnCode = null;
		$time1 = $t1->gettime();
		$time2 = $t2->gettime();
		if ($time1 > $time2)
		{
			$returnCode = self::ALATERTANB;
		}
		else if ($time1 == $time2)
		{
			$returnCode = self::AEQUALTOB;
		}
		else 
		{
			$returnCode = self::AEARLIERTHANB;
		}
		
		return $returnCode;
	}
	
	/**
	 * 
	 * 构造体
	 * @param long $t
	 */
	function __construct($t)
	{
		$this->time = $t;
	}

	/**
	 * 早于某个时间
	 * 
	 * @param long time
	 */
	function earlierThan($time)
	{
		if ($this->time < $time)
			return true;
		else 
			return false;
	}
	/**
	 * 晚于某个时间
	 * 
	 * @param long time
	 */
	function laterThan($time)
	{
		if ($this->time > $time)
			return true;
		else
			return false;
	}
	/**
	 * 
	 * @param PPTTime anotherTime
	 */
	function getTimeSpan($anotherTime)
	{
		$anTime =$anotherTime->gettime();
		return $this->time - $anTime;
	}
	
	function gettime()
	{
		return $this->time;
	}
	/**
	 * 
	 * @param newVal
	 */
	function settime($newVal)
	{
		$this->time = $newVal;
	}

}
?>