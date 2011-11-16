<?php
class Models_Behavior_Journal_PDeleteJournalComment extends Models_Behavior_PIBehavior
{
	public function __construct()
	{
		parent::__construct(Models_Behavior_PBehaviorEnum::BDELETEJOURNALCOMMENT);
	}
	
	/* (non-PHPdoc)
	 * @see Models_Behavior_PIBehavior::todo()
	 */
	protected function todo() {
//		throw new Zend_XmlRpc_Server_Exception('I am empty now ');
		if (!$this->isPropertySet())
		{
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_DELETEJOURNALCOMMENT_MISS_PARAMETER));
		}

		$usrId = $this->propertys[Models_Behavior_PBehaviorEnum::PDELETEJOURNALCOMMENT_USERID];
		$commentId = $this->propertys[Models_Behavior_PBehaviorEnum::PDELETEJOURNALCOMMENT_COMMENTID];
		
		$conn = Models_Core::getDoctrineConn();
		
		$query = Doctrine_Query::create()
				->delete('TrJournalComment jc')
				->where('jc.id = ?' , $commentId)
				->andWhere('jc.usr_id_ref = ?' , $usrId);
		
		try {
			$conn->beginTransaction();
			
			$result = $query->execute();
			
			$conn->commit();
			
			if ($result > 0)
			{
				return array('STATUS'=>intval(Models_Core::STATE_REQUEST_SUCCESS));
			}
			else
			{
				return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_DELETEJOURNALCOMMENT_COMMENT_UNEXIST));
			}
			
		} catch (Exception $e)
		{
			$conn->rollback();
			return array('STATUS'=>intval(Models_Core::STATE_DB_ERROR));
		}
	}
}
