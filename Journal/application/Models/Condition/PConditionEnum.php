<?php
class Models_Condition_PConditionEnum
{
	//common dir
	const COMMONDIR = 'Models_Condition_';
	
	//const C+名称 = (num);
	const CUSERID = 1;
	
	private static $names  = array(
		self::CUSERID => 'PUserId'
	);
	/**
	 * 
	 * 获得检索类的名字
	 * @param int $conditionId
	 */
	public static function getConditionClassName($conditionId)
	{
		return self::COMMONDIR . self::$names[$conditionId];
	}
}
