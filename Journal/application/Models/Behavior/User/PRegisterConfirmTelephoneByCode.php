<?php
class Models_Behavior_User_PRegisterConfirmTelephoneByCode extends Models_Behavior_PIBehavior
{
	public function __construct()
	{
		parent::__construct(Models_Behavior_PBehaviorEnum::BREGISTERCONFIRMTELEPHONEBYCODE);
	}
	
	/* (non-PHPdoc)
	 * @see Models_Behavior_PIBehavior::todo()
	 */
	protected function todo() {
		// TODO Auto-generated method stub
//		throw new Zend_XmlRpc_Server_Exception('I am empty now ');
		
		if (!$this->isPropertySet())
		{
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_REGISTER_CONFIRM_TELEPHONE_BY_CODE_MISS_PARAMETER));
		}
		
		$verifyCode = $this->propertys[Models_Behavior_PBehaviorEnum::PREGISTERCONFIRMTELEPHONEBYCODE_VERIFYCODE];
		
		$conn = Models_Core::getDoctrineConn();
		
		$query = Doctrine_Query::create()
				->from('TrRegisterTelephoneInfo rti')
				->where('rti.verifycode = ?' , $verifyCode);

		$rti = $query->fetchOne();
		
		if (!$rti)
		{
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_REGISTER_CONFIRM_TELEPHONE_BY_CODE_MISS_PARAMETER));
		}
		
		try {
			$conn->beginTransaction();
			
			$password = $rti->password;
			$username = $rti->username;
			$telephone = $rti->telephone;
			
			$user= new TrUser();
			$user->password = $password;
			$user->username = $username;
			$user->telephone = $telephone;
			
			$rti->status = 1;
			
			$user->save();
			$rti->save();
			
			$conn->commit();
			
			return array('STATUS'=>intval(Models_Core::STATE_REQUEST_SUCCESS));
			
		}catch(Exception $e)
		{
			$conn->rollback();
			
			return array('STATUS'=>intval(Models_Core::STATE_DB_ERROR));
		}
	}
}



