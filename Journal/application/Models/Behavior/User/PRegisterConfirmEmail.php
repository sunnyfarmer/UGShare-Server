<?php
class Models_Behavior_User_PRegisterConfirmEmail extends Models_Behavior_PIBehavior
{
	public function __construct()
	{
		parent::__construct(Models_Behavior_PBehaviorEnum::BREGISTERCONFIRMEMAIL);
	}
	
	/* (non-PHPdoc)
	 * @see Models_Behavior_PIBehavior::todo()
	 */
	protected function todo() {
		//如果确认码不存在，那么直接返回缺少参数的状态
		if (!$this->isPropertySet())
		{
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_REGISTER_CONFIRM_EMAIL_MISS_PARAMETER));
		}
		//获取注册确认码
		$verifycode = $this->propertys[Models_Behavior_PBehaviorEnum::PREGISTERCONFIRMEMAIL_VERIFYCODE];
		
		//设置Doctrine数据库连接
		$conn = Models_Core::getDoctrineConn();
		
		//利用确认码，获得注册信息
		$q = Doctrine_Query::create()
			->from('TrRegisterEmailInfo r')
			->where('r.verifycode = ?' , $verifycode);
		if (!$q->count())					//如果有返回值，即邮箱地址已经被注册，那么返回失败状态
		{
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_REGISTER_CONFIRM_EMAIL_VERIFYCODE_INAVALID));		
		}
		$tempInfo = $q->fetchOne();			//获得一个注册临时信息对象
		$email = $tempInfo->email;			//获得注册临时信息
		$username = $tempInfo->username;
		$password = $tempInfo->password;
		
		//添加用户信息
		try
		{
			$conn->beginTransaction();
			
			$usr = new TrUser();
			$usr->password = $password;
			$usr->username = $username;
			$usr->TrEmail[]->address = $email;
		
			$tempInfo->status = 1;
			$conn->flush();					//保存数据库对象
			
			$conn->commit();
			
			return array('STATUS'=>intval(Models_Core::STATE_REQUEST_SUCCESS));
		}
		catch(PDOException $e)
		{
			$conn->rollback();
			
			return array('STATUS'=>intval(Models_Core::STATE_DB_ERROR));
		}	
		
	}
}