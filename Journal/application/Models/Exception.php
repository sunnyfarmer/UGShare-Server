<?php
class Models_Exception extends Exception
{
	public static $errorMessage = array(
		Models_Core::ERR_BEHAVIOR_ID_NULL => 'behavior id null',		
		Models_Core::ERR_BEHAVIOR_PROPERTY_WRONG_FORMAT => 'behavior wrong property format',
		Models_Core::ERR_BEHAVIOR_LINE_ID_NULL => 'behavior line id null',
		Models_Core::ERR_BEHAVIOR_PROPERTY_MARK_WRONG_FORMAT => 'behvior wrong property mark format',
		Models_Core::ERR_DATATYPE_METHOD_NOT_EXIST => 'datatype compare method not exist',
		Models_Core::ERR_DATABASE_CONNECT_FAILED => 'database connect failed'
	);
	
	public function __construct($errValue , $code = 0)
	{
		parent::__construct(self::$errorMessage[$errValue], $code);
	}
}
