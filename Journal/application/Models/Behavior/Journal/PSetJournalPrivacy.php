<?php
class Models_Behavior_Journal_PSetJournalPrivacy extends Models_Behavior_PIBehavior
{
	public function __construct()
	{
		parent::__construct(Models_Behavior_PBehaviorEnum::BSETJOURNALPRIVACY);
	}
	
	/* (non-PHPdoc)
	 * @see Models_Behavior_PIBehavior::todo()
	 */
	protected function todo() {
//		throw new Zend_XmlRpc_Server_Exception('I am empty now ');
	
		if (!$this->isPropertySet())
		{
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_SETJOURNALPRIVACY_MISS_PARAMETER));
		}
		
		$usrId = $this->propertys[Models_Behavior_PBehaviorEnum::PSETJOURNALPRIVACY_USERID];
		$jId = $this->propertys[Models_Behavior_PBehaviorEnum::PSETJOURNALPRIVACY_JOURNALID];
		$privacy = $this->propertys[Models_Behavior_PBehaviorEnum::PSETJOURNALPRIVACY_ISPRIVATE];
		
		$privacy = $privacy ? 1 : 0;
		
		$conn = Models_Core::getDoctrineConn();
		
		$query = Doctrine_Query::create()
				->update('TrJournal j')
				->set('j.isPrivate' , $privacy)
				->where('j.id = ?' , $jId)
				->andWhere('j.usr_id_ref = ?' , $usrId);
		
		$conn->beginTransaction();
		
		try {
			$result = $query->execute();
			
			$conn->commit();
			if ($result)
			{
				return array('STATUS'=>intval(Models_Core::STATE_REQUEST_SUCCESS));
			}
			else 
			{
				return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_SETJOURNALPRIVACY_NO_PRIVILEGE));
			}
		}
		catch (Exception $e)
		{
			$conn->rollback();
			return array('STATUS'=>intval(Models_Core::STATE_DB_ERROR));
		}
	}
}