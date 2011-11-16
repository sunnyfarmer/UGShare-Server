<?php
class Models_Behavior_Geographic_PDeletePlace extends Models_Behavior_PIBehavior
{
	public function __construct()
	{
		parent::__construct(Models_Behavior_PBehaviorEnum::BDELETEPLACE);
	}
	
	/* (non-PHPdoc)
	 * @see Models_Behavior_PIBehavior::todo()
	 */
	protected function todo() {
//		throw new Zend_XmlRpc_Server_Exception('I am empty now ');
		if (!$this->isPropertySet())
		{
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_DELETEPLACE_MISS_PARAMETER));
		}
		$usrId = $this->propertys[Models_Behavior_PBehaviorEnum::PDELETEPLACE_USERID];
		$placeId = $this->propertys[Models_Behavior_PBehaviorEnum::PDELETEPLACE_PLACEID];
		
		$conn = Models_Core::getDoctrineConn();
		
		try
		{
			$conn->beginTransaction();
		
			$query = Doctrine_Query::create()
					->delete('TrPlace p')
					->where('p.id = ?' , $placeId);
			
			$rows = $query->execute();
			$conn->commit();
			if ($rows)
			{
				return array('STATUS'=>intval(Models_Core::STATE_REQUEST_SUCCESS));
			}	
			else
			{
				return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_DELETEPLACE_PLACEID_INVALID));
			}
		}
		catch (Exception $e)
		{
			$conn->rollback();
			return array('STATUS'=>intval(Models_Core::STATE_DB_ERROR));
		}
	}	
}