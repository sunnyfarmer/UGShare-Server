<?php
class Models_CommonAction_PAvatar
{
	const BIG_VERSION = 0;
	const SMALL_VERSION = 1;
	
	private $fileName = null;
	private $avatar   = null;
	
	private $bigAvatar = null;
	private $smallAvatar = null;
	
	private $bigAvatarBase64 = null;
	private $smallAvatarBase64 = null;
	
	private $bigAvatarBinary = null;
	private $smallAvatarBinary = null;
	
	private $avatarWidth = null;
	private $avatarHeight = null;
	
	/**
	 * 
	 * @param string $avatar
	 */
	public function __construct($avatar)
	{
		if (is_file($avatar))
		{
			$this->fileName = $avatar;
			$this->avatar = imagecreatefromjpeg($this->fileName);	
		}
		else
		{
			$this->avatar = imagecreatefromstring($avatar);
		}
		if ($this->avatar)
		{
			$this->avatarWidth = imagesx($this->avatar);
			$this->avatarHeight = imagesy($this->avatar);
		}
	}
	
	/**
	 * 
	 * 获取头像
	 */
	public function getBigAvatar()
	{
		if (!$this->bigAvatar)
		{
			if ($this->getAvatar())
			{
				$newWidth = Models_Core::AVATAR_BIG_WIDTH;
				$newHeight = Models_Core::AVATAR_BIG_HEIGHT;

				// Load
	            $this->bigAvatar = imagecreatetruecolor($newWidth, $newHeight);
	            // Resize
	            imagecopyresampled($this->bigAvatar, $this->avatar, 0, 0, 0, 0, $newWidth, $newHeight, $this->avatarWidth, $this->avatarHeight);	
			}
		}
		
		return $this->bigAvatar;
	}
	/**
	 * 
	 * 获取头像缩略图
	 */
	public function getSmallAvatar()
	{
		if (!$this->smallAvatar)
		{
			$newWidth = Models_Core::AVATAR_SMALL_WIDTH;
			$newHeight = Models_Core::AVATAR_SMALL_HEIGHT;

			// Load
            $this->smallAvatar = imagecreatetruecolor($newWidth, $newHeight);
            // Resize
            imagecopyresampled($this->smallAvatar, $this->avatar, 0, 0, 0, 0, $newWidth, $newHeight, $this->avatarWidth, $this->avatarHeight);	
		} 
		
		return $this->smallAvatar;
	}
	
	/**
	 * 
	 * 获取头像的base64码
	 */
	public function getBigAvatarBase64()
	{
		if (!$this->bigAvatarBase64)
		{
			if ($this->bigAvatarBinary)
			{
				$this->bigAvatarBase64 = base64_encode($this->bigAvatarBinary);
			}
			else if ($this->getBigAvatar())
			{
				$bigPath = PHOTO_TEMP_PATH.'/'.uniqid('ABIG').'.jpg';
				
				//将图片保存到文件中
				imagejpeg($this->getBigAvatar() , $bigPath , 100);
			
				//将文件读取到字符串中
				$this->bigAvatarBinary = file_get_contents($bigPath);
				
				//删除文件
				unlink($bigPath);
				
				$this->bigAvatarBase64 = base64_encode($this->bigAvatarBinary);
			}
		}
			
		return $this->bigAvatarBase64;
	}	
	/**
	 * @return the $bigAvatarBinary
	 */
	public function getBigAvatarBinary() {
		if (!$this->bigAvatarBinary)
		{
			if ($this->bigAvatarBase64)
			{
				$this->bigAvatarBinary = base64_decode($this->bigAvatarBase64);
			}
			else if ($this->getBigAvatar())
			{
				$bigPath = PHOTO_TEMP_PATH.'/'.uniqid('ABIG').'.jpg';
				
				//将图片保存到文件中
				imagejpeg($this->getBigAvatar() , $bigPath , 100);
			
				//将文件读取到字符串中
				$this->bigAvatarBinary = file_get_contents($bigPath);
				
				//删除文件
				unlink($bigPath);				
			}
		}
	
		return $this->bigAvatarBinary;
	}

	/**
	 * @return the $smallAvatarBinary
	 */
	public function getSmallAvatarBinary() {
		if (!$this->smallAvatarBinary)
		{
			if ($this->smallAvatarBase64)
			{
				$this->smallAvatarBinary = base64_decode($this->smallAvatarBase64);
			}
			else if ($this->getSmallAvatar())
			{
				$smallPath = PHOTO_TEMP_PATH.'/'.uniqid('AS').'.jpg';
				
				//将图片保存到文件中
				imagejpeg($this->getSmallAvatar() , $smallPath , 100);

				//将文件读取到字符串中
				$this->smallAvatarBinary = file_get_contents($smallPath);

				//删除文件
				unlink($smallPath);
			}
		}

		return $this->smallAvatarBinary;
	}
	/**
	 * 
	 * 获取头像缩略图的base64码
	 */
	public function getSmallAvatarBase64()
	{
		if (!$this->smallAvatarBase64)
		{
			if ($this->smallAvatarBinary)
			{
				$this->smallAvatarBase64 = base64_encode($this->smallAvatarBinary);
			}
			else if ($this->getSmallAvatar())
			{
				$smallPath = PHOTO_TEMP_PATH.'/'.uniqid('AS').'.jpg';
				
				//将图片保存到文件中
				imagejpeg($this->getSmallAvatar() , $smallPath , 100);

				//将文件读取到字符串中
				$this->smallAvatarBinary = file_get_contents($smallPath);

				//删除文件
				unlink($smallPath);
				
				$this->smallAvatarBase64 = base64_encode($this->smallAvatarBinary);
			}
		}

		return $this->smallAvatarBase64;
	}

	/**
	 * @return the $fileName
	 */
	public function getFileName() {
		return $this->fileName;
	}

	/**
	 * @return the $avatarWidth
	 */
	public function getAvatarWidth() {
		return $this->avatarWidth;
	}

	/**
	 * @return the $avatarHeight
	 */
	public function getAvatarHeight() {
		return $this->avatarHeight;
	}
	/**
	 * @return the $avatar
	 */
	public function getAvatar() {
		return $this->avatar;
	}

	/**
	 * 
	 * save the avatar photo into temp floder
	 * @param string $avatarId
	 * @param string $avatarStr
	 * @param int	$avatarVersion	BIG_VERSION/SMALL_VERSION
	 * @return string	web url of the avatar photo
	 */
	public static function saveToTemp($avatarId , $avatarStr , $avatarVersion)
	{
		$photo = new Models_CommonAction_PPhoto($avatarStr);
	
		//generate a unique file name base on avatar id
		$fileName = md5($avatarVersion.'avatar'.$avatarId);
		$path = $photo->saveAsFile(AVATAR_TEMP_PATH, $fileName);
		
		$relativePath = Models_CommonAction_PUrl::convertAbsoToRela(PROJECT_PATH, $path);
		$photoUrl = PROJECTURL.str_replace("\\", "/", $relativePath);
    
		return $photoUrl;
	}
}