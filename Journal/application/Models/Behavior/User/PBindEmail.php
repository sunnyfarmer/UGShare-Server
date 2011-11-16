<?php
class Models_Behavior_User_PBindEmail extends Models_Behavior_PIBehavior
{
	public function __construct()
	{
		parent::__construct(Models_Behavior_PBehaviorEnum::BBINDEMAIL);
	}
	
	/* (non-PHPdoc)
	 * @see Models_Behavior_PIBehavior::todo()
	 */
	protected function todo() {
		// TODO Auto-generated method stub
//		throw new Zend_XmlRpc_Server_Exception('I am empty now ');

		if (!$this->isPropertySet())
		{
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_BINDEMAIL_MISS_PARAMETER));
		}
		
		$usrId = $this->propertys[Models_Behavior_PBehaviorEnum::PBINDEMAIL_USERID];
		$email = $this->propertys[Models_Behavior_PBehaviorEnum::PBINDEMAIL_EMAIL];
		
		$conn = Models_Core::getDoctrineConn();
		
		$user = Doctrine_Core::getTable('TrUser')->find($usrId);
		
		if ($user)
		{
			$conn->beginTransaction();
			try 
			{
				$oldEmail = $user->TrEmail[0]->address;
				
				if (!Models_CommonAction_PDataJudge::isAddress($oldEmail))
				{
					$user->TrEmail[0]->address = $email;
					$user->save();
				
					$conn->commit();
					return array('STATUS'=>intval(Models_Core::STATE_REQUEST_SUCCESS)); 
				}
				else
				{
					$conn->rollback();
					return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_BINDEMAIL_BINDED)); 
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
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_BINDEMAIL_USERID_INVALID));
		}
	}
}