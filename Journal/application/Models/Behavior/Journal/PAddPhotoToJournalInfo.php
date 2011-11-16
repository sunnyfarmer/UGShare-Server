<?php
class Models_Behavior_Journal_PAddPhotoToJournalInfo extends Models_Behavior_PIBehavior
{
	public function __construct()
	{
		parent::__construct(Models_Behavior_PBehaviorEnum::BADDPHOTOTOJOURNALINFO);
	}
	
	/* (non-PHPdoc)
	 * @see Models_Behavior_PIBehavior::todo()
	 */
	protected function todo() {
//		throw new Zend_XmlRpc_Server_Exception('I am empty now ');
		if (!$this->isPropertySet(Models_Behavior_PBehaviorEnum::PADDPHOTOTOJOURNALINFO_USERID)
			|| !$this->isPropertySet(Models_Behavior_PBehaviorEnum::PADDPHOTOTOJOURNALINFO_INFOID)
			|| !$this->isPropertySet(Models_Behavior_PBehaviorEnum::PADDPHOTOTOJOURNALINFO_PHOTO))
		{
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_ADDPHOTOTOJOURNALINFO_MISS_PARAMETER));
		}
		
		//获得参数
		$usrId = $this->propertys[Models_Behavior_PBehaviorEnum::PADDPHOTOTOJOURNALINFO_USERID];
		$infoId = $this->propertys[Models_Behavior_PBehaviorEnum::PADDPHOTOTOJOURNALINFO_INFOID];
		$photoTitle = $this->propertys[Models_Behavior_PBehaviorEnum::PADDPHOTOTOJOURNALINFO_PHOTOTITLE];
		$photoBase64Str = $this->propertys[Models_Behavior_PBehaviorEnum::PADDPHOTOTOJOURNALINFO_PHOTO];
		
		//连接数据库
		$conn = Models_Core::getDoctrineConn();
		
		try
		{
			$photoStr = base64_decode($photoBase64Str);
			
			//解析成图片
			$photoObj = new Models_CommonAction_PPhoto($photoStr);
			$photoObj->addText(Models_Core::PHOTO_TAG, 
				Models_CommonAction_PPhoto::getFontDir(Models_Core::PHOTO_TAG_FONT_FILE), 
				Models_CommonAction_PPhoto::$WIHTE
				);
			if ($photoObj->getPhoto())
			{
				$conn->beginTransaction();
				
				$photoStr_Origin = $photoObj->getPhotoBinary();		
				$photoStr_PC = $photoObj->getPcPhotoBinary();
				$photoStr_Mobile = $photoObj->getMobilePhotoBinary();
				$photoStr_Small = $photoObj->getSmallPhotoBinary();		
				
				//获得新的游记图片数据对象
				$jpiPhoto = new TrPhoto();
				
				$jpiPhoto->jpi_id_ref = $infoId;
				$jpiPhoto->title = $photoTitle;
				$jpiPhoto->pcVersion = $photoStr_PC;
				$jpiPhoto->mobileVersion = $photoStr_Mobile;
				$jpiPhoto->smallVersion = $photoStr_Small;
				$jpiPhoto->originVersion = $photoStr_Origin;
				
				$jpiPhoto->save();

				if ($jpiPhoto->TrJournalPlaceInfo->TrJournalPlace->TrJournal->TrUser->id != $usrId)
				{
					throw new Exception('user id is not matched');
				}
				
				$conn->commit();
				
				$jpiPhotoId = $jpiPhoto->id;

				return array('STATUS'=>intval(Models_Core::STATE_REQUEST_SUCCESS) , 'DATA'=>array('PHOTOID'=>$jpiPhotoId));
			}
			else
			{
				return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_ADDPHOTOTOJOURNALINFO_PHOTO_INVALID));
			}
		}
		catch(Exception $e)
		{
			$conn->rollback();
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_ADDPHOTOTOJOURNALINFO_PARAMETER_INVALID));	
		}
	}
}