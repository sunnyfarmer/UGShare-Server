<?php
abstract class Models_Condition_PCondition
{
	/**
	 * 
	 * 检测条件
	 * @param string $userId	用户的id
	 */
	public abstract function check($userId);
}
