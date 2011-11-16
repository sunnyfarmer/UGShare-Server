<?php
class Models_Behavior_User_PFollowUserById extends Models_Behavior_PIBehavior
{
	public function __construct()
	{
		parent::__construct(Models_Behavior_PBehaviorEnum::BFOLLOWUSERBYID);
	}
	
	/* (non-PHPdoc)
	 * @see Models_Behavior_PIBehavior::todo()
	 */
	protected function todo() {
		// TODO Auto-generated method stub
//		throw new Zend_XmlRpc_Server_Exception('I am empty now ');

		if (!$this->isPropertySet())
		{
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_FOLLOWUSERBYID_MISS_PARAMETER));
		}
		$usrId = $this->propertys[Models_Behavior_PBehaviorEnum::PFOLLOWUSERBYID_USERID];
		$ids = $this->propertys[Models_Behavior_PBehaviorEnum::PFOLLOWUSERBYID_IDS];
		
		if (!is_array($ids))
		{
			$ids = array($ids);
		}
		
		$conn = Models_Core::getDoctrineConn();
		
		$conn->beginTransaction();
		
		try 
		{
			foreach ($ids as $id)
			{
				$utu = new TrUserToUser();
				
				$utu->usr_id_self_ref = $usrId;
				$utu->usr_id_other_ref = $id;
				
				$utu->save();
			}
			$conn->commit();
			return array('STATUS'=>intval(Models_Core::STATE_REQUEST_SUCCESS));
		}
		catch (Exception $e)
		{
			$conn->rollback();
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_FOLLOWUSERBYID_FOLLOWED));
		}
	}
}

