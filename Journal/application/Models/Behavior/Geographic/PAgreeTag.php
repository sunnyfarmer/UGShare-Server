<?php
class Models_Behavior_Geographic_PAgreeTag extends Models_Behavior_PIBehavior
{
	public function __construct()
	{
		parent::__construct(Models_Behavior_PBehaviorEnum::BAGREETAG);
	}
	/* (non-PHPdoc)
	 * @see Models_Behavior_PIBehavior::todo()
	 */
	protected function todo() {
		// TODO Auto-generated method stub
		if (!$this->isPropertySet())
		{
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_AGREETAG_MISS_PARAMETE));
		}
		
		$usrId = $this->propertys[Models_Behavior_PBehaviorEnum::PAGREETAG_USERID];
		$placeTagId = $this->propertys[Models_Behavior_PBehaviorEnum::PAGREETAG_PLACETAGID];
		
		$conn = Models_Core::getDoctrineConn();
		
		$conn->beginTransaction();
		try 
		{
			$placeTag = Doctrine_Core::getTable('TrPlaceTag')->find($placeTagId);
			if ($placeTagId)
			{
				$placeTag->agreeCount++;
				$placeTag->save();
			}
			else
			{
				$conn->commit();
				return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_AGREETAG_PLACETAGID_INVALID));
			}

			$conn->commit();
			return array('STATUS'=>intval(Models_Core::STATE_REQUEST_SUCCESS));
		}catch (Exception $e)
		{
			$conn->rollback();
			return array('STATUS'=>intval(Models_Core::STATE_DB_ERROR));
		}
	}
}