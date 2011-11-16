<?php
//require_once dirname(__File__).'./PDataTypeEnum.php';
//require_once dirname(dirname(__FILE__)).'./loader/PAutoloader.php';
class Models_Datatype_PDatatypeManager
{
	static function getInstance($typeID , $para)
	{
		$className = Models_Datatype_PDataTypeEnum::$PROPERTYTYPE_ARRAY[$typeID];
		return new $className($para);
	}   
	
	/**
	 * 
	 * 比较属性
	 * @param string $comparisionID
	 * @param unknown_type $para
	 * @param unknown_type $value
	 * @throws Exception
	 */
	static function compare($comparisionID , $para , $value)
	{
		$instance = self::getInstance(self::getTypeID($comparisionID), $para);
		$compareName = Models_Datatype_PDataTypeEnum::$COMPARISION_ARRAY[$comparisionID];
		
		if(! method_exists($instance,$compareName) )
		{	//如果函数不存在，那么抛出异常
			throw new Models_Exception(Models_Core::ERR_DATATYPE_METHOD_NOT_EXIST);
			return false;
		}
		else 
		{
			return $instance->{$compareName}($value);
		}
	}
	static function getTypeID($comparisionID)
	{
		return intval($comparisionID/Models_Datatype_PDataTypeEnum::POWER);
	}
}