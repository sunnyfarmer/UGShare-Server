<?php
class Models_Behavior_Im_PClearBlackList extends Models_Behavior_PIBehavior
{
	public function __construct()
	{
		parent::__construct(Models_Behavior_PBehaviorEnum::BCLEARBLACKLIST);
	}
	
	/* (non-PHPdoc)
	 * @see Models_Behavior_PIBehavior::todo()
	 */
	protected function todo() {
		throw new Zend_XmlRpc_Server_Exception('I am empty now ');
	}


}