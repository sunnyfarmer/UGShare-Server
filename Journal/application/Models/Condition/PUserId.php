<?php
class Models_Condition_PUserId extends Models_Condition_PCondition
{
	/**
	 * 
	 * 检测条件
	 * @param string $userId	用户的id
	 */
	public function check($userId)
	{
		//检测用户的用户id是否大于十，》10则true，否则false
		if ( $userId > 10 ) 
			return true;
		else
			return false;
	}
}