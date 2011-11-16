<?php
class Models_Behavior_User_PLoginEmail extends Models_Behavior_PIBehavior
{
	public function __construct()
	{
		parent::__construct(Models_Behavior_PBehaviorEnum::BLOGINEMAIL);
	}
	
	/* (non-PHPdoc)
	 * @see Models_Behavior_PIBehavior::todo()
	 */
	protected function todo() {
		//get the behavior propertys first
		$email = $this->propertys[Models_Behavior_PBehaviorEnum::PLOGINEMAIL_EMAIL];
		$password = $this->propertys[Models_Behavior_PBehaviorEnum::PLOGINEMAIL_PASSWORD];
		
		//connect the database first
		Models_Core::getDoctrineConn();
		
		//check if the it is a valid account
		$q = Doctrine_Query::create()
			->select('u.id')
			->from('TrUser u')
			->leftJoin('u.TrEmail e')
			->where('e.address = ?' , $email)
			->andWhere('u.password = ?' , $password);
		//if count > 0 , then get authorization
		if ($q->count())
		{
			// get userId
			$q->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
			$usr = $q->fetchOne();
			$usrId = $usr['id'];
			//authorization
			Models_CommonAction_PAuthorization::authorization($usrId);
			
			//return the success state
			return array('STATUS'=>intval(Models_Core::STATE_REQUEST_SUCCESS));
		}
		else 
		{
			//判断该邮箱地址是否处于待确认的注册列表中
			$q = Doctrine_Query::create()
				->from('TrRegisterEmailInfo r')
				->where('r.email = ?' , $email);
			if ($q->count())					//如果有返回值，即邮箱地址已经被注册，那么返回失败状态
			{
				return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_REGISTER_EMAIL_REGISTERING));
			}

			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_LOGIN_USER_OR_SECRET_WRONG));
		}	
	}
}
