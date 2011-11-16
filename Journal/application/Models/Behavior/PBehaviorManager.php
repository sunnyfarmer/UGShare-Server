<?php
class Models_Behavior_PBehaviorManager
{
	/**
	 * 
	 * get the instance 
	 * @param string $class
	 * @return Models_Behavior_PIBehavior
	 */
	static function getInstance($class)
	{
		$instance = null;
		if (class_exists($class))//if $class is the class name, return the instance directly
		{
			$instance = new $class();		
		}
		else if(null != ($className = Models_Behavior_PBehaviorEnum::getName($class)) )//if $class is id, get the class name first , then return the instance
		{
			$instance = new $className();
		}
		return $instance;
	}
}