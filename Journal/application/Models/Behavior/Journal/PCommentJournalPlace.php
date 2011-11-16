<?php
class Models_Behavior_Journal_PCommentJournalPlace extends Models_Behavior_PIBehavior
{
	public function __construct()
	{
		parent::__construct(Models_Behavior_PBehaviorEnum::BCOMMENTJOURNALPLACE);
	}
	
	/* (non-PHPdoc)
	 * @see Models_Behavior_PIBehavior::todo()
	 */
	protected function todo() {
//		throw new Zend_XmlRpc_Server_Exception('I am empty now ');
		if (!$this->isPropertySet())
		{
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_COMMENTJOURNAL_MISS_PARAMETER));
		}
		$usrId = $this->propertys[Models_Behavior_PBehaviorEnum::PCOMMENTJOURNALPLACE_USERID];
		$jpId = $this->propertys[Models_Behavior_PBehaviorEnum::PCOMMENTJOURNALPLACE_JOURLANPLACEID];
		$comment = $this->propertys[Models_Behavior_PBehaviorEnum::PCOMMENTJOURNALPLACE_COMMENT];
		
		$conn = Models_Core::getDoctrineConn();
		
		try 
		{
			$conn->beginTransaction();
			
			$jpCommentToDb = new TrJournalPlaceComment();
			$jpCommentToDb->usr_id_ref = $usrId;
			$jpCommentToDb->jpc_id_ref = $jpId;
			$jpCommentToDb->comment_text = $comment;
			$jpCommentToDb->save();
			
			$conn->commit();
			return array('STATUS'=>intval(Models_Core::STATE_REQUEST_SUCCESS));
		}
		catch (Exception $e)
		{
			$conn->rollback();
			return array('STATUS'=>intval(Models_Core::STATE_DB_ERROR));
		}
	}


}