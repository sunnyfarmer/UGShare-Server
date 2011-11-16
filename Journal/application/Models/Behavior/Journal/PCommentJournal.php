<?php
class Models_Behavior_Journal_PCommentJournal extends Models_Behavior_PIBehavior
{
	public function __construct()
	{
		parent::__construct(Models_Behavior_PBehaviorEnum::BCOMMENTJOURNAL);
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
		$usrId = $this->propertys[Models_Behavior_PBehaviorEnum::PCOMMENTJOURNAL_USERID];
		$journalId = $this->propertys[Models_Behavior_PBehaviorEnum::PCOMMENTJOURNAL_JOURNALID];
		$comment = $this->propertys[Models_Behavior_PBehaviorEnum::PCOMMENTJOURNAL_COMMENT];
		
		$conn = Models_Core::getDoctrineConn();
		
		try 
		{
			$conn->beginTransaction();
			$commentToDB = new TrJournalComment();
			$commentToDB->usr_id_ref = $usrId;
			$commentToDB->jnl_id_ref =$journalId;
			$commentToDB->comment_text = $comment;
			$commentToDB->save();
			
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