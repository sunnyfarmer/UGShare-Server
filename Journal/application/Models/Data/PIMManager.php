<?php
class Models_Data_PIMManager
{
	/**
	 * 
	 * 获得用户黑名单用户的列表
	 * @param string $usrId
	 * @param int $beginIndex
	 * @param int $rowCount
	 * @return struct	array(usrId=>usrName , usrId=>usrName , ...)
	 */
	public static function getBlackList($usrId , $beginIndex , $rowCount)
	{
		throw new Zend_XmlRpc_Server_Exception('I am empty now');
	}
}