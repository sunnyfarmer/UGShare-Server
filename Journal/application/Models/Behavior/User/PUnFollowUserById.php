<?php
class Models_Behavior_User_PUnFollowUserById extends Models_Behavior_PIBehavior
{
	public function __construct()
	{
		parent::__construct(Models_Behavior_PBehaviorEnum::BUNFOLLOWUSERBYID);
	}
	
	/* (non-PHPdoc)
	 * @see Models_Behavior_PIBehavior::todo()
	 */
	protected function todo() {
		// TODO Auto-generated method stub
//		throw new Zend_XmlRpc_Server_Exception('I am empty now ');

		if (!$this->isPropertySet())
		{
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_UNFOLLOWUSERBYID_MISS_PARAMETER));
		}
		$usrId = $this->propertys[Models_Behavior_PBehaviorEnum::PUNFOLLOWUSERBYID_USERID];
		$ids = $this->propertys[Models_Behavior_PBehaviorEnum::PUNFOLLOWUSERBYID_IDS];
		
		$conn = Models_Core::getDoctrineConn();
		
		if (!is_array($ids))
		{
			$ids = array($ids);
		}
		
		$idStr = '';
		foreach ($ids as $id)
		{
			$idStr .= "$id,";
		}
		$idStr = '(' . rtrim($idStr , ',') . ')';
	
		$query = Doctrine_Query::create()
				->delete('TrUserToUser utu')
				->where('utu.usr_id_self_ref = ?' , $usrId)
				->andWhere('utu.usr_id_other_ref in ' . $idStr)
				;
		try {
			$conn->beginTransaction();
			
			$result = $query->execute();
			
			$conn->commit();
			if ($result>0)
				return array('STATUS'=>intval(Models_Core::STATE_REQUEST_SUCCESS));
			else 
				return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_UNFOLLOWUSERBYID_USER_UNEXIST));
		}catch (Exception $e)
		{
			$conn->rollback();
			return array('STATUS'=>intval(Models_Core::STATE_DB_ERROR));
			
		}
	}

}