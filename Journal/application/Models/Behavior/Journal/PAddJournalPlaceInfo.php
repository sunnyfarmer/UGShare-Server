<?php
class Models_Behavior_Journal_PAddJournalPlaceInfo extends Models_Behavior_PIBehavior
{
	public function __construct()
	{
		parent::__construct(Models_Behavior_PBehaviorEnum::BADDJOURNALPLACEINFO);
	}
	
	/* (non-PHPdoc)
	 * @see Models_Behavior_PIBehavior::todo()
	 */
	protected function todo() {
//		throw new Zend_XmlRpc_Server_Exception('I am empty now ');
		if (!$this->isPropertySet())
		{
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_ADDJOURNALPLACEINFO_MISS_PARAMETER));		
		}
		
		//获得参数
		$usrId = $this->propertys[Models_Behavior_PBehaviorEnum::PADDJOURNALPLACEINFO_USERID];
		$journaPlaceId = $this->propertys[Models_Behavior_PBehaviorEnum::PADDJOURNALPLACEINFO_JOURNALPLACEID];
		$infoText = $this->propertys[Models_Behavior_PBehaviorEnum::PADDJOURNALPLACEINFO_INFOTEXT];
		$latitude = $this->propertys[Models_Behavior_PBehaviorEnum::PADDJOURNALPLACEINFO_LATITUDE];
		$longitude = $this->propertys[Models_Behavior_PBehaviorEnum::PADDJOURNALPLACEINFO_LONGITUDE];
		
		//连接数据库
		$conn = Models_Core::getDoctrineConn();

		try
		{
			$conn->beginTransaction();
			
			//添加一个游记内容
			$jpi = new TrJournalPlaceInfo();
		
			$jpi->jpc_id_ref = $journaPlaceId;
			$jpi->info = $infoText;
			$jpi->latitude = $latitude;
			$jpi->longitude = $longitude;
			
			//保存游记内容
			$jpi->save();
			$jpInfoId = $jpi->id;
			
			if ($jpi->TrJournalPlace->TrJournal->TrUser->id != $usrId)
			{
				throw new Exception('user_id is not matched');
			}
			
			$conn->commit();
			return array('STATUS'=>intval(Models_Core::STATE_REQUEST_SUCCESS) , 'DATA'=>array('INFOID'=>$jpInfoId));
		}
		catch(Exception $e)
		{
			$conn->rollback();
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_ADDJOURNALPLACEINFO_PARAMETER_INVALID));
		}
	}
}