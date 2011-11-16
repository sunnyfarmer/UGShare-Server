<?php
class Models_Behavior_User_PLoginTelephone extends Models_Behavior_PIBehavior
{
	public function __construct()
	{
		parent::__construct(Models_Behavior_PBehaviorEnum::BLOGINTELEPHONE);
	}
	
	/* (non-PHPdoc)
	 * @see Models_Behavior_PIBehavior::todo()
	 */
	protected function todo() {
		//get the login info property 
		$telephone = $this->propertys[Models_Behavior_PBehaviorEnum::PLOGINTELEPHONE_TELEPHONE];
		$password = $this->propertys[Models_Behavior_PBehaviorEnum::PLOGINTELEPHONE_PASSWORD];
		
		//get the connection
		Models_Core::getDoctrineConn();
		
		//check if the it is a valid account
		$q = Doctrine_Query::create()
			->select('u.id')
			->from('TrUser u')
			->where('u.telephone = ?' , $telephone)
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
			//判断该手机号是否处于待确认的注册列表中
			$q = Doctrine_Query::create()
				->from('TrRegisterTelephoneInfo r')
				->where('r.telephone = ?' , $telephone);
			if($q->count())
			{
				return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_REGISTER_TELEPHONE_MSG_TIME_RESTRICT));
			}
			
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_LOGIN_USER_OR_SECRET_WRONG));
		}	
	}
}
