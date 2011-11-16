<?php
class Models_Behavior_User_PRegisterOthers extends Models_Behavior_PIBehavior
{
	public function __construct()
	{
		parent::__construct(Models_Behavior_PBehaviorEnum::BREGISTEROTHERS);
	}
	
	/* (non-PHPdoc)
	 * @see Models_Behavior_PIBehavior::todo()
	 */
	protected function todo() {
		throw new Zend_XmlRpc_Server_Exception('I am empty now ');
	}
}