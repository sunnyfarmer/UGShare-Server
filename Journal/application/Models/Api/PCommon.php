<?php
class Models_Api_PCommon
{
	/* Namespace <<<*/
	const NS_CORE = 'core';
	const NS_USER = 'user';
	const NS_SYSTEM = 'system';
	const NS_JOURNAL = 'journal';
	const NS_IM = 'im';
	const NS_GEOGRAPHIC = 'geographic';
	/* Namespace >>>*/
	/**
	 * 
	 * 所有对外提供的接口类
	 * @var array	形式如，array(namespace=>className)
	 */
	static $interfaceClass = array(
    	self::NS_CORE => array(
    		'Models_Api_Core_PRpcCoreMethod'
		), 
    	self::NS_USER => array(
    		'Models_Api_User_PRpcLogin', 
    		'Models_Api_User_PRpcRegister', 
    		'Models_Api_User_PRpcUserInfo', 	
    		'Models_Api_User_PRpcUserMethod'
		), 
    	self::NS_SYSTEM => array(
    		'Models_Api_System_PRpcOnlineRecordInfo'
		), 
    	self::NS_JOURNAL => array(
    		'Models_Api_Journal_PRpcJournalInfo', 
    		'Models_Api_Journal_PRpcJournalMethod'), 
    	self::NS_IM => array(
    		'Models_Api_Im_PRpcImInfo',
    		'Models_Api_Im_PRpcImMethod'
		), 
    	self::NS_GEOGRAPHIC => array(
    		'Models_Api_Geographic_PRpcGeographicInfo', 
    		'Models_Api_Geographic_PRpcGeographicMethod'
		)
	); 
}