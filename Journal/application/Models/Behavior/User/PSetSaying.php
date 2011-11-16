<?php
class Models_Behavior_User_PSetSaying extends Models_Behavior_PIBehavior
{
	public function __construct()
	{
		parent::__construct(Models_Behavior_PBehaviorEnum::BSETSAYING);
	}
	
	/* (non-PHPdoc)
	 * @see Models_Behavior_PIBehavior::todo()
	 */
	protected function todo() {
//		throw new Zend_XmlRpc_Server_Exception('I am empty now ');
	
		if (!$this->isPropertySet())
		{
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_SETSAYING_MISS_PARAMETER));
		}
		
		$usrId = $this->propertys[Models_Behavior_PBehaviorEnum::PSETSAYING_USERID];
		$saying = $this->propertys[Models_Behavior_PBehaviorEnum::PSETSAYING_SAYING];
	
		$conn = Models_Core::getDoctrineConn();	
		
		$sayingToDb = new TrSaying();
		
		$conn->beginTransaction();
		try
		{
			$sayingToDb->usr_id_ref = $usrId;
			$sayingToDb->saying = $saying;

			$sayingToDb->save();
			$conn->commit();
			return array('STATUS'=>intval(Models_Core::STATE_REQUEST_SUCCESS));
		}
		catch (Exception $e)
		{
			$conn->rollback();
			return array('STATUS'=>intval(Models_Core::STATE_DB_ERROR));
		}
	}
}

