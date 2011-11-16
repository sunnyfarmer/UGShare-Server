<?php
class Models_Behavior_Journal_PCreateJournal extends Models_Behavior_PIBehavior
{
	public function __construct()
	{
		parent::__construct(Models_Behavior_PBehaviorEnum::BCREATEJOURNAL);
	}
	
	/* (non-PHPdoc)
	 * @see Models_Behavior_PIBehavior::todo()
	 */
	protected function todo() {
//		throw new Zend_XmlRpc_Server_Exception('I am empty now ');
		if(!$this->isPropertySet())
		{
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_CREATEJOURNAL_MISS_PARAMETER));
		}
		
		$usrId = $this->propertys[Models_Behavior_PBehaviorEnum::PCREATEJOURNAL_USERID];
		$title = $this->propertys[Models_Behavior_PBehaviorEnum::PCREATEJOURNAL_TITLE];
		$isPrivate = $this->propertys[Models_Behavior_PBehaviorEnum::PCREATEJOURNAL_ISPRIVATE];
		
		$conn = Models_Core::getDoctrineConn();
		
		try
		{
			$conn->beginTransaction();
	
			//添加新的游记
			$newJournalRecord = new TrJournal();
			$newJournalRecord->usr_id_ref = $usrId;
			$newJournalRecord->title = $title;			
			if ($isPrivate)
			{
				$newJournalRecord->isPrivate = 1;		
			}
			//保存新的游记
			$newJournalRecord->save();
			$journalId = $newJournalRecord->id;
			
			//更新新游记的更新时间
			$newJournalRecord->updateTime = $newJournalRecord->time;
			$newJournalRecord->save();
			
			$conn->commit();

			return array('STATUS'=>intval(Models_Core::STATE_REQUEST_SUCCESS) , 'DATA'=>array('JOURNALID'=>$journalId));
		}
		catch(PDOException $e)
		{
			$conn->rollback();
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_CREATEJOURNAL_PARAMETER_INVALID));
		}	
	}
}