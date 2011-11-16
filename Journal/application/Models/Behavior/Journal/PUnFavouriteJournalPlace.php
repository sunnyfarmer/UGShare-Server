<?php
class Models_Behavior_Journal_PUnFavouriteJournalPlace extends Models_Behavior_PIBehavior
{
	public function __construct()
	{
		parent::__construct(Models_Behavior_PBehaviorEnum::BUNFAVOURITEJOURNALPLACE);
	}
	
	/* (non-PHPdoc)
	 * @see Models_Behavior_PIBehavior::todo()
	 */
	protected function todo() {
	//		throw new Zend_XmlRpc_Server_Exception('I am empty now ');
		if (!$this->isPropertySet())
		{
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_UNFAVOURITEJOURNALPLACE_MISS_PARAMETER));
		}
	
		$usrId = $this->propertys[Models_Behavior_PBehaviorEnum::PUNFAVOURITEJOURNALPLACE_USERID];
		$jpId = $this->propertys[Models_Behavior_PBehaviorEnum::PUNFAVOURITEJOURNALPLACE_JOURNALPLACEID];
		
		$conn = Models_Core::getDoctrineConn();
		
		$query = Doctrine_Query::create()
				->delete('TrJournalPlaceFavouriteList jpfl')
				->where('jpfl.jpc_id_ref = ?' , $jpId)
				->andWhere('jpfl.usr_id_ref = ?' , $usrId);
		
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