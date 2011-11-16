<?php
class Models_Behavior_Journal_PFavouriteJournalPlace extends Models_Behavior_PIBehavior
{
	public function __construct()
	{
		parent::__construct(Models_Behavior_PBehaviorEnum::BFAVOURITEJOURNALPLACE);
	}
	
	/* (non-PHPdoc)
	 * @see Models_Behavior_PIBehavior::todo()
	 */
	protected function todo() {
//		throw new Zend_XmlRpc_Server_Exception('I am empty now ');
		if (!$this->isPropertySet(Models_Behavior_PBehaviorEnum::PFAVOURITEJOURNALPLACE_USERID)
		|| !$this->isPropertySet(Models_Behavior_PBehaviorEnum::PFAVOURITEJOURNALPLACE_JOURNALPLACEID)	
		)
		{
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_FAVOURITEJOURNALPLACE_MISS_PARAMETER));
		}

		$conn = Models_Core::getDoctrineConn();
		
		//获得参数
		$usrId = $this->propertys[Models_Behavior_PBehaviorEnum::PFAVOURITEJOURNALPLACE_USERID];
		$jpId = $this->propertys[Models_Behavior_PBehaviorEnum::PFAVOURITEJOURNALPLACE_JOURNALPLACEID];
		$comment = $this->propertys[Models_Behavior_PBehaviorEnum::PFAVOURITEJOURNALPLACE_COMMENT];
		
		//直接存储了
		try 
		{
			$conn->beginTransaction();
			
			//添加收藏游记景点列表
			$favJP = new TrJournalPlaceFavouriteList();
			
			$favJP->usr_id_ref = $usrId;
			$favJP->jpc_id_ref = $jpId;
			$favJP->comment = $comment;
			//保存收藏
			$favJP->save();
			
			$conn->commit();
			return array('STATUS'=>intval(Models_Core::STATE_REQUEST_SUCCESS));
		}
		catch (Exception $e)
		{
			$conn->rollback();
		
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_FAVOURITEJOURNALPLACE_PARAMENTER_INVALID));
		}
	}


}