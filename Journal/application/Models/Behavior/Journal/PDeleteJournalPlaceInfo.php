<?php
class Models_Behavior_Journal_PDeleteJournalPlaceInfo extends Models_Behavior_PIBehavior
{
	public function __construct()
	{
		parent::__construct(Models_Behavior_PBehaviorEnum::BDELETEJOURNALPLACEINFO);
	}
	
	/* (non-PHPdoc)
	 * @see Models_Behavior_PIBehavior::todo()
	 */
	protected function todo() {
//		throw new Zend_XmlRpc_Server_Exception('I am empty now ');
	
		if (!$this->isPropertySet())
		{
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_DELETEJOURNALPLACEINFO_MISS_PARAMETER));
		}

		$usrId = $this->propertys[Models_Behavior_PBehaviorEnum::PDELETEJOURNALPLACEINFO_USERID];
		$infoId = $this->propertys[Models_Behavior_PBehaviorEnum::PDELETEJOURNALPLACEINFO_INFOID];
		
		$conn = Models_Core::getDoctrineConn();
		
		$query = Doctrine_Query::create()
				->select('jpi.id')
				->from('TrJournalPlaceInfo jpi')
				->leftJoin('jpi.TrJournalPlace jp')
				->leftJoin('jp.TrJournal j')
				->where('jpi.id = ?' , $infoId)
				->andWhere('j.usr_id_ref = ?' , $usrId);
		
		$jpi = $query->fetchOne();
				
		if ($jpi)
		{
			$query = Doctrine_Query::create()
					->delete('TrJournalPlaceInfo jpi')
					->where('jpi.id = ?' , $infoId);
			try {
				$conn->beginTransaction();

				$query->execute();
				
				$conn->commit();
				
				return array('STATUS'=>intval(Models_Core::STATE_REQUEST_SUCCESS));
			} catch (Exception $e)
			{
				$conn->rollback();
							
				return array('STATUS'=>intval(Models_Core::STATE_DB_ERROR));
			}
		}
		else
		{
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_DELETEJOURNALPLACEINFO_INFOID_UNEXIST));
		}			
	}
}
