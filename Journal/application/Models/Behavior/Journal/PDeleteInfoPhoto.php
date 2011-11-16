<?php
class Models_Behavior_Journal_PDeleteInfoPhoto extends Models_Behavior_PIBehavior
{
	public function __construct()
	{
		parent::__construct(Models_Behavior_PBehaviorEnum::BDELETEINFOPHOTO);
	}
	
	/* (non-PHPdoc)
	 * @see Models_Behavior_PIBehavior::todo()
	 */
	protected function todo() {
//		throw new Zend_XmlRpc_Server_Exception('I am empty now ');
	
		if (!$this->isPropertySet())
		{
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_DELETEINFOPHOTO_MISS_PARAMETER));
		}

		$usrId = $this->propertys[Models_Behavior_PBehaviorEnum::PDELETEINFOPHOTO_USERID];
		$photoId = $this->propertys[Models_Behavior_PBehaviorEnum::PDELETEINFOPHOTO_PHOTOID];
		
		$conn = Models_Core::getDoctrineConn();

		$query = Doctrine_Query::create()
				->select('p.id')
				->from('TrPhoto p')
				->leftJoin('p.TrJournalPlaceInfo jpi')
				->leftJoin('jpi.TrJournalPlace jp')
				->leftJoin('jp.TrJournal j')
				->where('p.id = ?' , $photoId)
				->andWhere('j.usr_id_ref = ?' , $usrId);
		$photo = $query->fetchOne();
		
		if ($photo)
		{
			$query = Doctrine_Query::create()
					->delete('TrPhoto p')
					->where('p.id = ?' , $photoId);
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
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_DELETEINFOPHOTO_PHOTO_UNEXIST));
		}
		
	}
}