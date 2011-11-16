<?php
class Models_Behavior_Journal_PUnFavouriteJournal extends Models_Behavior_PIBehavior
{
	public function __construct()
	{
		parent::__construct(Models_Behavior_PBehaviorEnum::BUNFAVOURITEJOURNAL);
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
	
		$usrId = $this->propertys[Models_Behavior_PBehaviorEnum::PUNFAVOURITEJOURNAL_USERID];
		$journalId = $this->propertys[Models_Behavior_PBehaviorEnum::PUNFAVOURITEJOURNAL_JOURNALID];
		
		$conn = Models_Core::getDoctrineConn();
		
		$query = Doctrine_Query::create()
				->delete('TrJournalFavouriteList jfl')
				->where('jfl.jnl_id_ref = ?' , $journalId)
				->andWhere('jfl.usr_id_ref = ?' , $usrId);
		
		try {
			$conn->beginTransaction();
			
			$query->execute();
			
			$conn->commit();
			
			return array('STATUS'=>intval(Models_Core::STATE_REQUEST_SUCCESS));
			
		} catch(Exception $e)
		{
			$conn->rollback();
						
			return array('STATUS'=>intval(Models_Core::STATE_DB_ERROR));
		}
	}
}