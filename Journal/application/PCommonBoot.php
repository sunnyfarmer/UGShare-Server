<?php
class PCommonBoot
{	
	public static function init()
	{
		$curFileDir = dirname(__FILE__);

		//Define PROJECTURL
		defined('PROJECTURL')
			|| define('PROJECTURL', 'http://localhost/Journal/');
		
		// Define WEBURL
		defined('WEBURL')
    		|| define('WEBURL', "http://localhost/Journal/public/index.php");

		// Define path to application directory
		defined('APPLICATION_PATH')
    		|| define('APPLICATION_PATH', realpath($curFileDir));

    	// Define path of project
    	defined('PROJECT_PATH')
    		|| define('PROJECT_PATH', realpath(APPLICATION_PATH . '/../'));	

		// Define application environment
		defined('APPLICATION_ENV')
    		|| define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

    	//定义behaviorNet.xml的路径
		defined('BEHAVIORNETXMLPATH')
    		|| define('BEHAVIORNETXMLPATH', realpath(APPLICATION_PATH . './configs/behaviorNet.xml'));	
    	//定义字体文件存储的路径
    	defined('FONT_PATH')
    		|| define('FONT_PATH', realpath(APPLICATION_PATH.'./res/font'));
    	//定义临时缓存文件存储的路径
    	defined('CACHE_TEMP_PATH')
    		|| define('CACHE_TEMP_PATH', realpath(APPLICATION_PATH.'./temp/cache'));
    	//定义临时照片文件存储的路径	
    	defined('PHOTO_TEMP_PATH')
    		|| define('PHOTO_TEMP_PATH', realpath(APPLICATION_PATH.'./temp/photo'));
    	//定义临时头像照片文件存储的路径
    	defined('AVATAR_TEMP_PATH')
    		|| define('AVATAR_TEMP_PATH', realpath(APPLICATION_PATH . './temp/avatar'));	

		// Ensure library/ is on include_path
		$oldIncludePath = get_include_path();

		$newIncludePath = implode(
			PATH_SEPARATOR, 
			array(
		    	realpath(APPLICATION_PATH . '/../library'),
		    	realpath(APPLICATION_PATH . '/../library/Doctrine-1.2.4'),
		    	realpath(APPLICATION_PATH . '/../library/restler'),
		    	realpath(APPLICATION_PATH),
		    	$oldIncludePath
			)
		);
    	set_include_path($newIncludePath);

    	//获取一个Zend_Loader_Autoloader的对象（默认注册了一个autoloader）
    	require_once 'Zend/Loader/Autoloader.php';
    	$loader = Zend_Loader_Autoloader::getInstance();
		$loader->registerNamespace('Models_');//注册Zend_Loader_A的namespace
    	
    	//注册doctrine的autoloader
    	require_once 'Doctrine.php';
    	spl_autoload_register(array('Doctrine', 'autoload'));
		//注册数据库模型的autoloader
		spl_autoload_register(array('Doctrine_Core', 'modelsAutoload'));
		$manager = Doctrine_Manager::getInstance();
		$manager->setAttribute(Doctrine_Core::ATTR_MODEL_LOADING, Doctrine_Core::MODEL_LOADING_CONSERVATIVE);
		Doctrine_Core::loadModels(realpath($curFileDir.'/Models/Doctrine'));   	
		//register Memcache server for Doctrine
		try {
			$cacheDriver = new Doctrine_Cache_Memcache(
				array(
					'servers' => Models_Core::$MEMCACHE_SERVER_MAP,
					'compression' => false
				)
			);
			$manager->setAttribute(Doctrine_Core::ATTR_QUERY_CACHE, $cacheDriver);
		}
		catch(Doctrine_Cache_Exception $e)
		{
//			echo "\noooo\n";
		}
	}
}
