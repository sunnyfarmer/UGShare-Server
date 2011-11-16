<?php
//require_once dirname(__FILE__)
class Models_Behavior_User_PRegisterEmail extends Models_Behavior_PIBehavior
{
	public function __construct()
	{
		parent::__construct(Models_Behavior_PBehaviorEnum::BREGISTEREMAIL);
	}
	
	/* (non-PHPdoc)
	 * @see PIBehavior::todo()
	 */
	protected function todo() {
		//首先判断是否所有的行为属性已经设置
		if (!self::isPropertySet())
		{
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_REGISTER_MISS_PARAMETER));
		}
		
		//获得注册信息
		$email = $this->propertys[Models_Behavior_PBehaviorEnum::PREGISTEREMAIL_EMAIL];			//注册邮箱地址
		$username = $this->propertys[Models_Behavior_PBehaviorEnum::PREGISTEREMAIL_USERNAME];	//注册用户的用户名	
		$password = $this->propertys[Models_Behavior_PBehaviorEnum::PREGISTEREMAIL_PASSWORD];	//注册用户密码
		
		//设置Doctrine数据库连接
		$conn = Models_Core::getDoctrineConn();
		
		//判断该邮箱地址是否已经被注册
		$q = Doctrine_Query::create()
			->from('TrEmail e')
			->where('e.address = ?' , $email);
		if ($q->count())					//如果有返回值，即邮箱地址已经被注册，那么返回失败状态
		{
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_REGISTER_EMAIL_REGISTERED));		
		}
		
		//判断该邮箱地址是否处于待确认的注册列表中
		$q = Doctrine_Query::create()
			->from('TrRegisterEmailInfo r')
			->where('r.email = ?' , $email);
		if ($q->count())					//如果有返回值，即邮箱地址已经被注册，那么返回失败状态
		{
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_REGISTER_EMAIL_REGISTERING));		
		}
		
		//保存注册临时信息
		try
		{
			$conn->beginTransaction();
			
			$tempInfo = new TrRegisterEmailInfo();
			$tempInfo->email = $email;
			$tempInfo->username = $username;
			$tempInfo->password = $password;
			$verifycode =  md5($email);
			$tempInfo->verifycode = $verifycode;
		
			$tempInfo->save();
			
			$conn->commit();	

			//过关后，发送确认邮件
			$this->sendConfirmEmail($email , $verifycode);
		
			return array('STATUS'=>intval(Models_Core::STATE_REQUEST_SUCCESS));
		}
		catch(Exception $e)
		{
			$conn->rollback();
			return array('STATUS'=>intval(Models_Core::STATE_DB_ERROR));
		}	

	}
	
	private static function sendConfirmEmail($email , $verifycode)
	{
		$url = WEBURL . '/register/confirmemail?verifycode=' . $verifycode;
		
		//read the html file into memory first
		$htmlDir = realpath(APPLICATION_PATH . '/views/scripts/register/confirmingEmailPage.html');
		$fp = fopen($htmlDir, 'rb');
		$content = fread($fp, filesize($htmlDir));
		$content = str_replace('?$email?', $email, $content);
		$content = str_replace('?$confirmUrl?', $url, $content);

		$sm = new Models_CommonAction_PEmail();	//新建一个邮件对象
		$sm->setTitle('确认邮件');			
		$sm->setBodyHtml($content);
		$sm->addTo($email);
		$sm->send();							//发送邮件
	}
}
