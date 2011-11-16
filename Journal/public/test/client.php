<?php
date_default_timezone_set('Asia/Shanghai');
$curPath = dirname(__FILE__);
$bootDir = realpath($curPath . '/../../application/PCommonBoot.php');
require_once $bootDir;
PCommonBoot::init(); //初始化设置

$client = new Zend_XmlRpc_Client('http://127.0.0.1:8080/search/MyServer');//http://127.0.0.1/Journal/public/xmlrpc/server.php');
//try {
//	echo $client->call('md5Value' , '123233');


//	$result = $client->call('user.login' , array('123456' , '123'));		

	$str = 'sunnyfarmer';
	$str = str_split($str);
	
	$rs = $client->call('my.hello' , array($str));	
	print_r($rs);
	
//} catch(Zend_Http_Client_Adapter_Exception $e){
//	echo "  never can! Zend_Http_Client_Adapter_Exception<br>";
//	echo $e->getCode()."<br>";
//	echo $e->getMessage()."<br>";
//}catch (Zend_XmlRpc_Client_HttpException $e) {
//	echo "  no, i can't<br>";
//	echo $e->getCode()."<br>";
//	echo $e->getMessage()."<br>";
//} catch (Zend_XmlRpc_Client_FaultException $e){
//	echo "  never can! Zend_XmlRpc_Client_FaultException<br>";
//	echo $e->getCode()."<br>";
//	echo $e->getMessage()."<br>";
//}catch(Exception $e){
//	echo "  never can! Exception<br>";
//	echo $e->getCode()."<br>";
//	echo $e->getMessage()."<br>";
//}
// hello

