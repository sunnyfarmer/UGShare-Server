<?php
class Models_Behavior_Geographic_PAddPlaceTag extends Models_Behavior_PIBehavior
{
	public function __construct()
	{
		parent::__construct(Models_Behavior_PBehaviorEnum::BADDPLACETAG);
	}
	/* (non-PHPdoc)
	 * @see Models_Behavior_PIBehavior::todo()
	 */
	protected function todo() {
		// TODO Auto-generated method stub
		if (!$this->isPropertySet())
		{
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_ADDPLACETAG_MISS_PARAMETER));
		}
		
		$usrId = $this->propertys[Models_Behavior_PBehaviorEnum::PADDPLACETAG_USERID];
		$placeId = $this->propertys[Models_Behavior_PBehaviorEnum::PADDPLACETAG_PLACEID];
		$tag = $this->propertys[Models_Behavior_PBehaviorEnum::PADDPLACETAG_TAG];
		
		$conn = Models_Core::getDoctrineConn();
		
		$conn->beginTransaction();
		
		try 
		{
			//先获得tag的id
			$query = Doctrine_Query::create()
					->select('tg.id')			
					->from('TrTag tg')
					->where('tg.tag_text = ?' , $tag);
			$tagFromDb = $query->fetchOne();
			$tagId = null;
			if($tagFromDb)
			{
				$tagId = $tagFromDb->id;
			}
			else
			{
				$tagFromDb = new TrTag();
				$tagFromDb->tag_text = $tag;
				
				$tagFromDb->save();
				$tagId = $tagFromDb->id;
			}
			
			$placeTag = new TrPlaceTag();
			$placeTag->plc_id_ref = $placeId;
			$placeTag->tag_id_ref = $tagId;
			
			$placeTag->save();
			
			$conn->commit();
			return array('STATUS'=>intval(Models_Core::STATE_REQUEST_SUCCESS));
		}catch (Exception $e)
		{
			$conn->rollback();
			return array('STATUS'=>intval(Models_Core::STATE_DB_ERROR));
		}
	}
}