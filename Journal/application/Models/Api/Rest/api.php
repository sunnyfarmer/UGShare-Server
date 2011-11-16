<?php
class api
{
	public function __call($name, $params)
	{
		$namespaces = Models_Api_PCommon::$interfaceClass[$this->NAME_SPACE];
		
		foreach ($namespaces as $namespace)
		{
			$object = new $namespace;
			if(method_exists($object, $name))
			{
				return call_user_method_array($name, $object, $params);
			}
		}
	}
	
}