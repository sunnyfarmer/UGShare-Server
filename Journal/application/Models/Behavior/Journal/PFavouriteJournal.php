<?php
class Models_Behavior_Journal_PFavouriteJournal extends Models_Behavior_PIBehavior
{
	public function __construct()
	{
		parent::__construct(Models_Behavior_PBehaviorEnum::BFAVOURITEJOURNAL);
	}
	
	/* (non-PHPdoc)
	 * @see Models_Behavior_PIBehavior::todo()
	 */
	protected function todo() {
//		throw new Zend_XmlRpc_Server_Exception('I am empty now ');
		if (!$this->isPropertySet(Models_Behavior_PBehaviorEnum::PFAVOURITEJOURNAL_USERID)
			|| !$this->isPropertySet(Models_Behavior_PBehaviorEnum::PFAVOURITEJOURNAL_JOURNALID)
		)
		{
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_FAVOURITEJOURNAL_MISS_PARAMETER));
		}
		
		$conn = Models_Core::getDoctrineConn();
		
		//获得参数
		$usrId = $this->propertys[Models_Behavior_PBehaviorEnum::PFAVOURITEJOURNAL_USERID];
		$journalId = $this->propertys[Models_Behavior_PBehaviorEnum::PFAVOURITEJOURNAL_JOURNALID];
		$comment = $this->propertys[Models_Behavior_PBehaviorEnum::PFAVOURITEJOURNAL_COMMENT];
		
		//直接存储了
		try 
		{
			$conn->beginInternalTransaction();
			//添加收藏列表
			$favJournal = new TrJournalFavouriteList();
			$favJournal->usr_id_ref = $usrId;
			$favJournal->jnl_id_ref	= $journalId;
			$favJournal->comment = $comment;
			//保存收藏列表
			$favJournal->save();
			
			$conn->commit();
			return array('STATUS'=>intval(Models_Core::STATE_REQUEST_SUCCESS));
		}	
		catch(Exception $e)
		{
			$conn->rollback();
			
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_FAVOURITEJOURNALPLACE_PARAMENTER_INVALID));
		}	
	}


}