<?php
class Models_Behavior_Journal_PDeleteJournal extends Models_Behavior_PIBehavior
{
	public function __construct()
	{
		parent::__construct(Models_Behavior_PBehaviorEnum::BDELETEJOURNAL);
	}
	
	/* (non-PHPdoc)
	 * @see Models_Behavior_PIBehavior::todo()
	 */
	protected function todo() {
//		throw new Zend_XmlRpc_Server_Exception('I am empty now ');
		if (!$this->isPropertySet())
		{
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_DELETEJOURNAL_MISS_PARAMETER));
		}
		
		$usrId = $this->propertys[Models_Behavior_PBehaviorEnum::PDELETEJOURNAL_USERID];
		$jId = $this->propertys[Models_Behavior_PBehaviorEnum::PDELETEJOURNAL_JOURNALID];
		
		$conn = Models_Core::getDoctrineConn();
		
		$conn->beginTransaction();
		
		$query = Doctrine_Query::create()
				->delete('TrJournal j')
				->where('j.id = ?' , $jId)
				->andWhere('j.usr_id_ref = ?' , $usrId);
		
		try {
			$result = $query->execute();
			
			$conn->commit();
			if ($result)
			{
				return array('STATUS'=>intval(Models_Core::STATE_REQUEST_SUCCESS));
			}
			else 
			{
				return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_DELETEJOURNAL_NO_PRIVILEGE));	
			}
		}
		catch (Exception $e)
		{
			$conn->rollback();
			return array('STATUS'=>intval(Models_Core::STATE_DB_ERROR));
		}
	}
}