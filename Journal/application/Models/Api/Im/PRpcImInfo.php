<?php
class Models_Api_Im_PRpcImInfo
{
	/**
	 * 
	 * 获取即时通信的黑名单
	 * @param int $beginIndex
	 * @param int $rowCount		
	 * @return struct	array('STATUS'=>int ,array('USERID'=>userid , 'USERNAME'=>username),array('USERID'=>userid , 'USERNAME'=>username) , ...)
	 */
	public static function getBlackList($beginIndex , $rowCount)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		
		$result = Models_Data_PIMManager::getBlackList($usrId, $beginIndex, $rowCount);
		
		return $result;
	}
}