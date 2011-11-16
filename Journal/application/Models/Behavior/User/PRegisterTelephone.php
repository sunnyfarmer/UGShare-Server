<?php
class Models_Behavior_User_PRegisterTelephone extends Models_Behavior_PIBehavior
{
	public function __construct()
	{
		parent::__construct(Models_Behavior_PBehaviorEnum::BREGISTERTELEPHONE);
	}
	
	/* (non-PHPdoc)
	 * @see Models_Behavior_PIBehavior::todo()
	 */
	protected function todo() {
		//首先判断是否所有的行为属性已经设置
		if (!self::isPropertySet())
		{
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_REGISTER_MISS_PARAMETER));
		}
		//获得注册信息
		$telephone = $this->propertys[Models_Behavior_PBehaviorEnum::PREGISTERTELEPHONE_TELEPHONE];		//注册邮箱地址
		$username = $this->propertys[Models_Behavior_PBehaviorEnum::PREGISTERTELEPHONE_USERNAME];		//注册用户的用户名
		$password = $this->propertys[Models_Behavior_PBehaviorEnum::PREGISTERTELEPHONE_PASSWORD];		//注册用户密码
		
		//设置Doctrine数据库连接
		$conn = Models_Core::getDoctrineConn();

		//判断该手机号是否已经被注册
		$q = Doctrine_Query::create()
			->from('TrUser u')
			->where('u.telephone = ?' , $telephone);
		if ($q->count())
		{
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_REGISTER_TELEPHONE_REGISTERED));
		}
		
		//判断该手机号是否处于待确认的注册列表中
		$q = Doctrine_Query::create()
			->from('TrRegisterTelephoneInfo r')
			->where('r.telephone = ?' , $telephone);
		if($q->count())
		{
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_REGISTER_TELEPHONE_MSG_TIME_RESTRICT));
		}
		//过关后，保存注册临时信息
		
		try
		{
			$conn->beginTransaction();
			
			$tempInfo = new TrRegisterTelephoneInfo();
			$tempInfo->telephone = $telephone;
			$tempInfo->username = $username;
			$tempInfo->password = $password;
			$verifycode =  md5($telephone);
			$tempInfo->verifycode = $verifycode;
			$tempInfo->save();
			
			$conn->commit();
			
			//发送确认短信
			$this->sendConfirmMsg($telephone ,$username, $verifycode);
			return array('STATUS'=>intval(Models_Core::STATE_REQUEST_SUCCESS));
		}
		catch(Exception $e)
		{
			$conn->rollback();
			return array('STATUS'=>intval(Models_Core::STATE_DB_ERROR));				
		}
	}
	private function sendConfirmMsg($telephone , $username,$verifycode)
	{
//		throw new Zend_XmlRpc_Server_Exception('I am empty now!!!!!');

		$url = WEBURL . '/register/confirmphone?verifycode=' . $verifycode;
		
		//read the html file into memory first
		$htmlDir = realpath(APPLICATION_PATH . '/views/scripts/register/confirmingTelePage.html');
		$fp = fopen($htmlDir, 'rb');
		$content = fread($fp, filesize($htmlDir));
		$content = str_replace('?$username?', $username, $content);
		$content = str_replace('?$confirmUrl?', $url, $content);	
		
		Models_CommonAction_PSms::sendsmsBySmsGate($telephone, $content);
	}

}

