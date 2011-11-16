<?php
class Models_Api_Core_PRpcCoreMethod
{
	/**
	 * 
	 * 同时调用多个接口
	 * @param array $methodName
	 * @param array $paraArrs
	 */
	public static function multicall($methodName , $paraArrs)
	{
		throw new Zend_XmlRpc_Exception('i am empty now');
	}

	/**
	 * 
	 * 嵌套调用
	 * @param string $script
	 */
	public static function nestcall($script)
	{
		$nestCaller = new Models_Api_Core_PRpcScript($script);
		$resultArray = $nestCaller->execute();
		
		return $resultArray;
	}
	
	/**
	 * 
	 * @param string $str
	 */
	public static function getString($str)
	{
		return $str;
	}
	/**
	 * 
	 * @param string $str
	 */
	public static function getTime($str)
	{
		return $str;
	}
	/**
	 * 
	 * @param int|long|float|double $str
	 */
	public static function getNumber($str)
	{
		return $str;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param array $arr
	 */
	public static function getArray($arr)
	{
		return $arr;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param array $arr
	 */
	public static function getStruct($arr)
	{
		$struct = null;
		$cot = 100;
		foreach ($arr as $value)
		{
			$struct['KEY'.$cot] = $value;
			$cot++;
		}
		
		return $struct;
	}
	
	/**
	 * 
	 * @param int|long|float|double $num
	 * @param string $str
	 */
	public static function numStr($num , $str)
	{
		return "$num+$str";
	}
	/**
	 * 
	 * @param int|long|float|double $num
	 * @param string $time
	 * @param string $str
	 */
	public static function numTimeStr($num , $time , $str)
	{
		return "$num+$time+$str";
	}
	/**
	 * 
	 * @param string $arr
	 * @param int|long|float|double $num
	 * @param string $time
	 * @param string $str
	 */
	public static function arrNumTimeStr($arr , $num , $time , $str)
	{
		foreach ($arr as &$value)
		{
			$value .= "$num+$time+$str";
		}
		return $arr;
	}
	
}

//date_default_timezone_set('UTC');
//$result = Models_Xmlrpc_Core_PRpcCoreMethod::getString('okok');
//echo "\n$result\n";
//
//$result = Models_Xmlrpc_Core_PRpcCoreMethod::getTime(date('c'));
//print_r($result);
//
//$result = Models_Xmlrpc_Core_PRpcCoreMethod::getNumber(123);
//echo "\n$result\n";
//
//$result = Models_Xmlrpc_Core_PRpcCoreMethod::getArray(array(1,2,3,4));
//print_r($result);
//
//$result = Models_Xmlrpc_Core_PRpcCoreMethod::getStruct(array(1,2,3,4));
//print_r($result);
//
//$num = 123;
//$str = 'abc';
//$result = Models_Xmlrpc_Core_PRpcCoreMethod::numStr($num, $str);
//echo "\n$result\n";
//
//$num = 123;
//$time = date('c');
//$str = 'abc';
//$result = Models_Xmlrpc_Core_PRpcCoreMethod::numTimeStr($num, $time, $str);
//echo "\n$result\n";
//
//$arr = array('aa' , 'bb' , 'cc' ,'dd');
//$num = 123;
//$time = date('c');
//$str = 'abc';
//$result = Models_Xmlrpc_Core_PRpcCoreMethod::arrNumTimeStr($arr, $num, $time, $str);
//print_r($result);


