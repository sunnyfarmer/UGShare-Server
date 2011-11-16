<?php
class Models_Behavior_Journal_PCreateJournalPlace extends Models_Behavior_PIBehavior
{
	public function __construct()
	{
		parent::__construct(Models_Behavior_PBehaviorEnum::BCREATEJOURNALPLACE);
	}
	
	/* (non-PHPdoc)
	 * @see Models_Behavior_PIBehavior::todo()
	 */
	protected function todo() {
//		throw new Zend_XmlRpc_Server_Exception('I am empty now ');
		//先检测userid、journalid、place是否有参数
		if (!$this->isPropertySet(Models_Behavior_PBehaviorEnum::PCREATEJOURNALPLACE_USERID)
			|| !$this->isPropertySet(Models_Behavior_PBehaviorEnum::PCREATEJOURNALPLACE_JOURNALID)
			|| !$this->isPropertySet(Models_Behavior_PBehaviorEnum::PCREATEJOURNALPLACE_PLACE))
		{
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_CREATEJOURNALPLACE_MISS_PARAMETER));
		}
		
		//获取所有参数
		$userID = $this->propertys[Models_Behavior_PBehaviorEnum::PCREATEJOURNALPLACE_USERID];
		$journalId = $this->propertys[Models_Behavior_PBehaviorEnum::PCREATEJOURNALPLACE_JOURNALID];
		$place = $this->propertys[Models_Behavior_PBehaviorEnum::PCREATEJOURNALPLACE_PLACE];
		
		//连接数据库
		$conn = Models_Core::getDoctrineConn();
		
		//获取place的id
		$query = Doctrine_Query::create()
				->select('p.id')		
				->from('TrPlace p')
				->where('p.name = ?' , $place)
				->orWhere('p.id = ?' , $place);
		$placeFromDB = $query->fetchOne();
		
		if ($placeFromDB)
		{	
			try
			{
				$conn->beginTransaction();
				
				$pId = $placeFromDB->id;			//获得地点的位置
				
				//添加新的游记景点
				$journalPlace = new TrJournalPlace();
				$journalPlace->plc_id_ref = $pId;
				$journalPlace->jnl_id_ref = $journalId;
				//保存游记景点 
				$journalPlace->save();
				$jPlaceId = $journalPlace->id;
				
				if ($journalPlace->TrJournal->TrUser->id != $userID)
				{
					throw new Zend_XmlRpc_Server_Exception('user_id is not matched');
				}
				
				//更新游记景点的更新时间
				$journalPlace->updateTime = $journalPlace->time;
				$journalPlace->save();
				
				$conn->commit();
				return array('STATUS'=>intval(Models_Core::STATE_REQUEST_SUCCESS) , 'DATA'=>array('JOURNALPLACEID'=>$jPlaceId));
			}
			catch(Exception $e)
			{
				$conn->rollback();
				return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_CREATEJOURNALPLACE_PARAMETER_INVALID));
			}
		}
		else
		{
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_CREATEJOURNALPLACE_PLACE_INVALID));
		}
	}
}