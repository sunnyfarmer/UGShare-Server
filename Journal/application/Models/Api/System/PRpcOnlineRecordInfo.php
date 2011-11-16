<?php
class Models_Api_System_PRpcOnlineRecordInfo
{
	/**
	 * 
	 * 获取某段时间内的在线人数
	 * @param string $beginTime
	 * @param string $endTime
	 * @return struct	array('STATUS'=>status ,'ONLINENUMBER'=>number)
	 */
	public static function getOnlineTotalNumber($beginTime , $endTime)
	{
		$result = Models_Data_PSystemBlogManager::getOnlineTotalNumber($beginTime, $endTime);
		
		return $result;
	}
}