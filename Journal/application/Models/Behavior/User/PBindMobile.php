<?php
class Models_Behavior_User_PBindMobile extends Models_Behavior_PIBehavior
{
	public function __construct()
	{
		parent::__construct(Models_Behavior_PBehaviorEnum::BBINDMOBILE);
	}
	
	/* (non-PHPdoc)
	 * @see Models_Behavior_PIBehavior::todo()
	 */
	protected function todo() {
		// TODO Auto-generated method stub
//		throw new Zend_XmlRpc_Server_Exception('I am empty now ');

		if (!$this->isPropertySet())
		{
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_BINDMOBILE_MISS_PARAMETER));
		}
		
		$usrId = $this->propertys[Models_Behavior_PBehaviorEnum::PBINDMOBILE_USERID];
		$mobile = $this->propertys[Models_Behavior_PBehaviorEnum::PBINDMOBILE_MOBILE];
		
		$conn = Models_Core::getDoctrineConn();
		
		$query = Doctrine_Query::create()
				->select('u.id , u.telephone')
				->from('TrUser u')
				->where('u.id = ?' , $usrId);
		$user = $query->fetchOne();
		
		if ($user) 
		{
			$conn->beginTransaction();
			try 
			{
				$oldMobile = $user->telephone;
				
				if (!Models_CommonAction_PDataJudge::isTelephone($oldMobile))
				{
					$user->telephone = $mobile;

					$user->save();

					$conn->commit();
					return array('STATUS'=>intval(Models_Core::STATE_REQUEST_SUCCESS));
				}
				else 
				{
					$conn->rollback();
					return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_BINDMOBILE_USERID_INVALID));
				}
			}
			catch (Exception $e)
			{
				$conn->rollback();
				return array('STATUS'=>intval(Models_Core::STATE_DB_ERROR));
			}
		}
		else
		{
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_BINDMOBILE_USERID_INVALID));
		}
	}
}