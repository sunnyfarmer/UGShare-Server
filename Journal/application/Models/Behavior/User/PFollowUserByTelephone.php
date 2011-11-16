<?php
class Models_Behavior_User_PFollowUserByTelephone extends Models_Behavior_PIBehavior
{
	public function __construct()
	{
		parent::__construct(Models_Behavior_PBehaviorEnum::BFOLLOWUSERBYTELEPHONE);
	}
	
	/* (non-PHPdoc)
	 * @see Models_Behavior_PIBehavior::todo()
	 */
	protected function todo() {
		// TODO Auto-generated method stub
//		throw new Zend_XmlRpc_Server_Exception('I am empty now ');
		
		if (!$this->isPropertySet())
		{
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_FOLLOWUSERBYTELEPHONE_MISS_PARAMETER));
		}
		$usrId = $this->propertys[Models_Behavior_PBehaviorEnum::PFOLLOWUSERBYTELEPHONE_USERID];
		$phones = $this->propertys[Models_Behavior_PBehaviorEnum::PFOLLOWUSERBYTELEPHONE_TELEPHONES];
		
		if (!is_array($phones))
		{
			$phones = array($phones);
		}
		
		$phoneStr = '';
		foreach ($phones as $phone)
		{
			$phoneStr .= "$phone,";
		}
		$phoneStr = rtrim($phoneStr , ' ');
		$phoneStr = rtrim($phoneStr , ',');
		$phoneStr = "($phoneStr)";
		
		$conn = Models_Core::getDoctrineConn();
		
		$query = Doctrine_Query::create()
				->select('u.id')
				->from('TrUser u')
				->where('u.telephone in ' . $phoneStr)
				->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
				
		$ids = $query->execute();
		$idArr = array();
		foreach ($ids as $id)
		{
			array_push($idArr, $id['id']);
		}
		
		$conn->beginTransaction();
		
		try 
		{
			foreach ($idArr as $id)
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
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_FOLLOWUSERBYTELEPHONE_FOLLOWED));
		}
		
	}
}


