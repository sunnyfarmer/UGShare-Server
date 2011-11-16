<?php
$restLibDir = realpath(PROJECT_PATH.'/library/restler/restler.php');
if ($restLibDir)
{
	require_once $restLibDir;
}

/**
 * 
 * 单例
 * @author samson
 *
 */
class Models_Api_PRest
{
	const CACHEPATH = './logs/rest.cache.php';

	const JSON = 1;
	const XML = 2;
	
	static $SUPPORTED_FORMAT = array
	(
		self::JSON => 'JsonFormat', 
		self::XML => 'XmlFormat'
	);
	
	/**
	 * 
	 * @var Models_Api_PRest
	 */
	static $singleton = null;

	/**
	 * 
	 * @var Restler
	 */
	private $server = null;
	
	public static function getSingleton()
	{
		if (!isset(Models_Api_PRest::$singleton) || !Models_Api_PRest::$singleton)
		{
			Models_Api_PRest::$singleton = new Models_Api_PRest();
		}
		
		return Models_Api_PRest::$singleton;
	}
	
	private function __construct(/*format...*/)
	{
		$params = func_get_args();
		$this->server = new Restler();
		
		$this->init($params);
	}
	
	public function init(/*format...*/)
	{
		$params = func_get_args();
		$this->setFormat($params);
		
		$this->setInterface();
	}
	
	public function setFormat(/*$format...*/) 
	{
		$params = func_get_args();
		$formats = array();
		foreach ($params as $formatCode)
		{
			if (isset(self::$SUPPORTED_FORMAT[$formatCode]))
			{
				array_push($formats, self::$SUPPORTED_FORMAT[$formatCode]);
			}
		}
		call_user_method_array(setSupportedFormats, $this->server, $formats);
	}
	
	public function setInterface()
	{
		foreach (Models_Api_PCommon::$interfaceClass as $namespace=>$classname)
		{
		
		}
	}
	
	public function saveCache()
	{
		$this->server->saveCache();
	}
	
	public function handle()
	{
		$this->server->handle();
	}
}

function autoload_Api_class($class)
{
	$apiDir = realpath(APPLICATION_PATH.'/Models/Api/Rest/');
	$filename = $apiDir.$class.'.php';
	if (file_exists($filename))
	{
		require_once $filename;
		return true;
	}
	else
	{
		return false;
	}
}