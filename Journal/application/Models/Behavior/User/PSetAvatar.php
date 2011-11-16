<?php
class Models_Behavior_User_PSetAvatar extends Models_Behavior_PIBehavior
{
	public function __construct()
	{
		parent::__construct(Models_Behavior_PBehaviorEnum::BSETAVATAR);
	}
	
	/* (non-PHPdoc)
	 * @see Models_Behavior_PIBehavior::todo()
	 */
	protected function todo() {
//		throw new Zend_XmlRpc_Server_Exception('I am not tested yet '); 
//		if (!$this->isPropertySet())
//		{
//			return array('STATUS'=>Models_Core::STATE_BEHAVIOR_SETAVATAR_MISS_PARAMETER);
//		}
//		$usrId = $this->propertys[Models_Behavior_PBehaviorEnum::PSETAVATAR_USERID];
//		$avatarStr = $this->propertys[Models_Behavior_PBehaviorEnum::PSETAVATAR_AVATAR];
//		
//		//创建头像
//		$image = new Models_CommonAction_PAvatar(base64_decode($avatarStr));
//		$bigAvatar = $image->getBigAvatarBinary();
//		$smallAvatar = $image->getSmallAvatarBinary();
//		
//		//连接数据库
//		$conn = Models_Core::getDoctrineConn();
//		$conn->beginTransaction();
//		
//		//先保存获得avatar id
//		$avatar = new TrAvatar();
//		$avatar->origin = $bigAvatar;
//		$avatar->small = $smallAvatar;
//		$avatar->save();					
//		
//		$avatarId = $avatar->id;
//		
//		//保存用户的外键
//		$user = Doctrine_Core::getTable('TrUser')->find($usrId);
//		if ($user)
//		{//如果有这个id，那么正常保存
//			$user->ava_id_ref = $avatarId;
//			$user->save();
//			$conn->commit();
//		}
//		else 
//		{//如果没有这个id
//			$conn->rollback();
//			return array('STATUS'=>Models_Core::STATE_BEHAVIOR_SETAVATAR_USERID_UNEXIST);
//		}
//		
//		return array('STATUS'=>Models_Core::STATE_REQUEST_SUCCESS);

		if (!$this->isPropertySet())
		{
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_SETAVATAR_MISS_PARAMETER));
		}
		$usrId = $this->propertys[Models_Behavior_PBehaviorEnum::PSETAVATAR_USERID];
		$avatarStr = $this->propertys[Models_Behavior_PBehaviorEnum::PSETAVATAR_AVATAR];
		
		//连接数据库
		$conn = Models_Core::getDoctrineConn();
		
		//获得旧的头像的id
		$query = Doctrine_Query::create()
				->select('u.id , u.ava_id_ref')
				->from('TrUser u')
				->where('u.id = ?' , $usrId);
		$user = $query->fetchOne();
		
		if ($user)
		{
			$conn->beginTransaction();
			try 
			{
				//删除旧的头像
				$oldAvaId = $user->ava_id_ref;
				if ($oldAvaId)
				{
					$query = Doctrine_Query::create()
							->delete('TrAvatar a')
							->where('a.id = ?' , $oldAvaId);
					$query->execute();
				}
				
				//创建头像
				$image = new Models_CommonAction_PAvatar(base64_decode($avatarStr));
				$bigAvatar = $image->getBigAvatarBinary();
				$smallAvatar = $image->getSmallAvatarBinary();
				
				//先保存获得avatar id
				$avatar = new TrAvatar();
				$avatar->origin = $bigAvatar;
				$avatar->small = $smallAvatar;
				$avatar->save();					
				
				$avatarId = $avatar->id;
				
				//保存用户的外键
				$user->ava_id_ref = $avatarId;
				$user->save();
				$conn->commit();
				return array('STATUS'=>intval(Models_Core::STATE_REQUEST_SUCCESS));
			}
			catch (Exception $e)
			{
				$conn->rollback();
				return array('STATUS'=>intval(Models_Core::STATE_DB_ERROR));
			}
		}
		else 
		{//如果没有这个id
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_SETAVATAR_USERID_UNEXIST));
		}
	}
}