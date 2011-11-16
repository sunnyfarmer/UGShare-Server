<?php
class Models_Behavior_User_PLoginOthers extends Models_Behavior_PIBehavior
{
	public function __construct()
	{
		parent::__construct(Models_Behavior_PBehaviorEnum::BLOGINOTHERS);
	}
	
	/* (non-PHPdoc)
	 * @see Models_Behavior_PIBehavior::todo()
	 */
	protected function todo() {
		// TODO Auto-generated method stub
		throw new Zend_XmlRpc_Server_Exception('I am empty now ');
	}
}