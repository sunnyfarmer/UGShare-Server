<?php
/**
 * 
 * 单例
 * @author samson
 *
 */
class Models_Api_PXmlrpc
{
	const CACHEPATH = '/logs/xmlrpc.cache';
	static $singleton = null;

	/**
	 * 
	 * 服务器对象
	 * @var Zend_XmlRpc_Server
	 */
	var $server = null;
	
	/**
	 * 
	 * 所有对外提供的接口函数
	 * @var array	形式如，array(namespace=>functionName)
	 */
	static $interfaceFunction = array(
	);
	
	private function __construct()
	{
		$this->init();
	}
	/**
	 * 
	 * 获得单例
	 * @return Models_Api_PServer	单例对象
	 */
	static function getSingleton()
	{
		if (null == self::$singleton)
		{
			self::$singleton = new self();
		}
		return self::$singleton;
	}
	
	/**
	 * 
	 * 初始化对象
	 */
	private function init()
	{	
		//创建server对象	
		$this->server = new Zend_XmlRpc_Server();
		//设置异常
		$this->setException();
		//设置对外接口
		$this->setInterface();
		//设置缓存
		$cachePath = realpath(APPLICATION_PATH.'/../'.self::CACHEPATH);
		$this->setCache($cachePath);
	}
	/**
	 * 
	 * 指定异常作为合法的失败响应
	 */
	private function setException()
	{
		// 允许 Services_Exceptions 作为响应失败输出
		Zend_XmlRpc_Server_Fault::attachFaultException('Services_Exception');
	}
	/**
	 * 
	 * 设置对外的接口
	 */
	private function setInterface()
	{
		foreach (Models_Api_PCommon::$interfaceClass as $namespace=>$classNames)
		{
			foreach($classNames as $classname)
			{
				$this->server->setClass($classname , $namespace);
			}
		}
		foreach(self::$interfaceFunction as $namespace=>$functionArray)
		{
			foreach($functionArray as $function)
			{
				$this->server->addFunction($function , $namespace);
			}
		}
	}
	/**
	 * 
	 * 设置缓存
	 */
	private function setCache($cachePath)
	{
		if (!Zend_XmlRpc_Server_Cache::get($cachePath, $this->server))
		{
			// 保存缓存
    		Zend_XmlRpc_Server_Cache::save($cachePath, $this->server);
		}
	}
	
	
	
	/**
	 * 
	 * 服务器运作
	 */
	public function work($request = false)
	{
		$responce = $this->server->handle($request);
/**
 * 写到文本文件中
 */
//$filepath = "D:/r.txt";
//$fopen = fopen($filepath, 'at');
//fwrite($fopen, $responce."\n");
//fclose($fopen);
		echo $responce;
//		echo $this->server->handle();
	}
}
