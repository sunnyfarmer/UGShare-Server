<?php
class Models_Behavior_User_PSetNewPassword extends Models_Behavior_PIBehavior
{
	public function __construct()
	{
		parent::__construct(Models_Behavior_PBehaviorEnum::BSETNEWPASSWORD);
	}
	
	/* (non-PHPdoc)
	 * @see Models_Behavior_PIBehavior::todo()
	 */
	protected function todo() {
		// TODO Auto-generated method stub
//		throw new Zend_XmlRpc_Server_Exception('I am empty now ');
	
		if(!$this->isPropertySet())
		{
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_SETNEWPASSWORD_MISS_PARAMETER));
		}
	
		$usrId = $this->propertys[Models_Behavior_PBehaviorEnum::PSETNEWPASSWORD_USERID];
		$oldPwd = $this->propertys[Models_Behavior_PBehaviorEnum::PSETNEWPASSWORD_OLDPWD];
		$newPwd = $this->propertys[Models_Behavior_PBehaviorEnum::PSETNEWPASSWORD_NEWPWD];
		
		$conn = Models_Core::getDoctrineConn();
		
		$query = Doctrine_Query::create()
				->update('TrUser u')
				->set('u.password' ,'?', $newPwd)
				->where('u.id = ? and u.password = ?' , array($usrId , $oldPwd));
				
		$conn->beginTransaction();
		try 
		{
			$result = $query->execute();
			
			$conn->commit();
			if ($result)
				return array('STATUS'=>intval(Models_Core::STATE_REQUEST_SUCCESS));
			else 
				return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_SETNEWPASSWORD_OLDPASSWORD_WRONG));	
		}
		catch (Exception $e)
		{
			$conn->rollback();
			return array('STATUS'=>intval(Models_Core::STATE_DB_ERROR));
		}	
	}
}


