<?php
date_default_timezone_set('Asia/Shanghai');
$curPath = dirname(__FILE__);
$bootDir = realpath($curPath . '/../../application/PCommonBoot.php');
require_once $bootDir;
PCommonBoot::init(); //初始化设置

$client = new Zend_XmlRpc_Client('http://127.0.0.1/Journal/public/xmlrpc/server.php');
try {

//	$fileName = 'D:\avatar\26.jpg';
//	$pContent = base64_encode(file_get_contents($fileName));

	$resultL = $client->call('user.login' , array('123456','123'));
//	$result = $client->call('user.setAvatar' , array($pContent));
	
	print_r($resultL);
//	echo '<br>';
//	print_r($result);
//	echo '<br>';
//	print_r($_COOKIE);

} catch(Zend_Http_Client_Adapter_Exception $e){
	echo "  never can! Zend_Http_Client_Adapter_Exception<br>";
	echo $e->getCode()."<br>";
	echo $e->getMessage()."<br>";
}catch (Zend_XmlRpc_Client_HttpException $e) {
	echo "  no, i can't<br>";
	echo $e->getCode()."<br>";
	echo $e->getMessage()."<br>";
} catch (Zend_XmlRpc_Client_FaultException $e){
	echo "  never can! Zend_XmlRpc_Client_FaultException<br>";
	echo $e->getCode()."<br>";
	echo $e->getMessage()."<br>";
}catch(Exception $e){
	echo "  never can! Exception<br>";
	echo $e->getCode()."<br>";
	echo $e->getMessage()."<br>";
}
// hello

