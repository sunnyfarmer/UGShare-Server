<?php
class Models_CommonAction_PPhoto
{
	const LEFTTOP = 0;				//左上角
	const LEFTBOTTOM = 1;			//左下角
	const RIGHTTOP = 2;				//右上角
	const RIGHTBOTTOM = 3;			//右下角
	
	const ORIGINAL_VERSION = 0;
	const PC_VERSION = 1;
	const MOBILE_VERSION = 2;
	const SMALL_VERSION = 3;
	
	public static $WIHTE = array(255,255,255);
	public static $BLACK = array(0 , 0 , 0);
	public static $GREY = array(128,128,128);

	private $fileName = null;
    
    private $photo = null;
    private $photoBase64 = null;
    private $photoBinary = null;
    private $originWidth = null;
    private $originHeight = null;

    private $pcPhoto = null;
    private $pcPhotoBase64 = null;
    private $pcPhotoBinary = null;
    private $pcWidth = null;
    private $pcHeight = null;
    
    private $mobilePhoto = null;
    private $mobilePhotoBase64 = null;
	private $mobilePhotoBinary = null;
    private $mobileWidth = null;
    private $mobileHeight = null;
    
    private $smallPhoto = null;
    private $smallPhotoBase64 = null;
	private $smallPhotoBinary = null;
    private $smallWidth = null;
    private $smallHeight = null;
    
    /**
     * 
     * @param string $photo
     */
    public function __construct ($photo)
    {
        if (is_file($photo)) {
            $this->fileName = $photo;
            $this->photo = imagecreatefromjpeg($this->fileName);
        } else {
            $this->photo = imagecreatefromstring($photo);
        }
        if ($this->photo) {
            $this->originWidth = imagesx($this->photo);
            $this->originHeight = imagesy($this->photo);
            $this->photoBinary = $photo;
        }
    }
    /**
     * @return the $fileName
     */
    public function getFileName ()
    {
        return $this->fileName;
    }

    /**
     * @return the $originWidth
     */
    public function getOriginWidth ()
    {
        if (! $this->originWidth) {
            if ($this->photo) {
                $this->originWidth = imagesx($this->photo);
            }
        }
        return $this->originWidth;
    }
    /**
     * @return the $originHeight
     */
    public function getOriginHeight ()
    {
        if (! $this->originHeight) {
            if ($this->photo) {
                $this->originHeight = imagesy($this->photo);
            }
        }
        return $this->originHeight;
    }

    /**
     * @return the $pcWidth
     */
    public function getPcWidth ()
    {
        return $this->pcWidth;
    }
    /**
     * @return the $pcHeight
     */
    public function getPcHeight ()
    {
        return $this->pcHeight;
    }

    /**
     * @return the $mobileWidth
     */
    public function getMobileWidth ()
    {
        return $this->mobileWidth;
    }
    /**
     * @return the $mobileHeight
     */
    public function getMobileHeight ()
    {
        return $this->mobileHeight;
    }

    /**
     * @return the $smallWidth
     */
    public function getSmallWidth ()
    {
        return $this->smallWidth;
    }
    /**
     * @return the $smallHeight
     */
    public function getSmallHeight ()
    {
        return $this->smallHeight;
    }
    /**
     * @return the $photo
     */
    public function getPhoto ()
    {
    	if (!$this->photo)
    	{	
    		if ($this->fileName)
    		{
    			$this->photo = imagecreatefromjpeg($this->fileName);
    		}
    	}
        return $this->photo;
    }
    /**
     * @param boolean $bReConvert		//是否需要重新转换图片
     * @return the $pcPhoto
     */
    public function getPcPhoto ($bReConvert = false)
    {
        if (!$this->pcPhoto || $bReConvert) 
        {
        	if ($this->getPhoto())
        	{
        		$newSize = self::parseSize($this->getOriginWidth(), $this->getOriginHeight(), Models_Core::PHOTO_PC_LIMIT_WIDTH, Models_Core::PHOTO_PC_LIMIT_HEIGHT);
        		$newWidth = $newSize[0];
				$newHeight = $newSize[1];
	
				// Load
	            $this->pcPhoto = imagecreatetruecolor($newWidth, $newHeight);
	            
	            // Resize
	            imagecopyresampled($this->pcPhoto, $this->photo, 0, 0, 0, 0, $newWidth, $newHeight, $this->originWidth, $this->originHeight);	
        		
				$this->pcWidth = $newWidth;
				$this->pcHeight = $newHeight;	            
        	}
        }
    	
        return $this->pcPhoto;
    }
    /**
     * @param boolean $bReConvert		//是否需要重新转换图片
     * @return the $mobilePhoto
     */
    public function getMobilePhoto ($bReConvert = false)
    {
    	if (!$this->mobilePhoto || $bReConvert)
    	{
    		if ($this->getPhoto())
    		{
    			$newSize = self::parseSize($this->getOriginWidth(), $this->getOriginHeight(), Models_Core::PHOTO_MOBILE_LIMIT_WIDTH, Models_Core::PHOTO_MOBILE_LIMIT_HEIGHT);
        		$newWidth = $newSize[0];
				$newHeight = $newSize[1];
	
				// Load
	            $this->mobilePhoto = imagecreatetruecolor($newWidth, $newHeight);
	            // Resize
	            imagecopyresampled($this->mobilePhoto, $this->photo, 0, 0, 0, 0, $newWidth, $newHeight, $this->originWidth, $this->originHeight);	
   
	            $this->mobileWidth = $newWidth;
	            $this->mobileHeight = $newHeight;
    		}
    	}
    	
        return $this->mobilePhoto;
    }
    /**
     * @param boolean $bReConvert		//是否需要重新转换图片
     * @return the $smallPhoto
     */
    public function getSmallPhoto ($bReConvert = false)
    {
    	if (!$this->smallPhoto || $bReConvert)
    	{
    		if ($this->getPhoto())
    		{
    			$newSize = self::parseSize($this->getOriginWidth(), $this->getOriginHeight(), Models_Core::PHOTO_SMALL_LIMIT_WIDTH, Models_Core::PHOTO_SMALL_LIMIT_HEIGH);
        		$newWidth = $newSize[0];
				$newHeight = $newSize[1];
	
				// Load
	            $this->smallPhoto = imagecreatetruecolor($newWidth, $newHeight);
	            // Resize
	            imagecopyresampled($this->smallPhoto, $this->photo, 0, 0, 0, 0, $newWidth, $newHeight, $this->originWidth, $this->originHeight);	
   
	            $this->mobileWidth = $newWidth;
	            $this->mobileHeight = $newHeight;
    		}
    	}
    	
        return $this->smallPhoto;
    }
    /**
     * @return the $photoBase64
     */
    public function getPhotoBase64 ()
    {
    	if (!$this->photoBase64)
		{
			if ($this->photoBinary)
			{
				$this->photoBase64 = base64_encode($this->photoBinary);
			}
			else if ($this->getPhoto())
			{
				$photoPath = PHOTO_TEMP_PATH.'/'.uniqid('PORI').'.jpg';
				
				//将图片保存到文件中
				imagejpeg($this->getPhoto() , $photoPath , 100);
			
				//将文件读取到字符串中
				$this->photoBinary = file_get_contents($photoPath);
				
				//删除文件
				unlink($photoPath);
				
				$this->photoBase64 = base64_encode($this->photoBinary);
			}
		}

		return $this->photoBase64;
    }
	/**
	 * @return the $photoBinary
	 */
	public function getPhotoBinary() {
		if (!$this->photoBinary)
		{
			if ($this->photoBase64)
			{
				$this->photoBinary = base64_decode($this->photoBase64);
			}
			else if ($this->getPhoto())
			{
				$photoPath = PHOTO_TEMP_PATH.'/'.uniqid('PORI').'.jpg';
				
				//将图片保存到文件中
				imagejpeg($this->getPhoto() , $photoPath , 100);
			
				//将文件读取到字符串中
				$this->photoBinary = file_get_contents($photoPath);
				
				//删除文件
				unlink($photoPath);
			}
		}
			
		return $this->photoBinary;
	}
    /**
     * @return the $pcPhotoBase64
     */
    public function getPcPhotoBase64 ()
    {
    	if (!$this->pcPhotoBase64)
		{
			if ($this->pcPhotoBinary)
			{
				$this->pcPhotoBase64 = base64_encode($this->pcPhotoBinary);
			}
			else if ($this->getPcPhoto())
			{
				$photoPath = PHOTO_TEMP_PATH.'/'.uniqid('PPCO').'.jpg';
				
				//将图片保存到文件中
				imagejpeg($this->getPcPhoto() , $photoPath , 100);
			
				//将文件读取到字符串中
				$this->pcPhotoBinary = file_get_contents($photoPath);
				
				//删除文件
				unlink($photoPath);
				
				$this->pcPhotoBase64 = base64_encode($this->pcPhotoBinary);
			}
		}
			
		return $this->pcPhotoBase64;
    }
	/**
	 * @return the $pcPhotoBinary
	 */
	public function getPcPhotoBinary() {
		if (!$this->pcPhotoBinary)
		{
			if ($this->pcPhotoBase64)
			{
				$this->pcPhotoBinary = base64_decode($this->pcPhotoBase64);
			}
			if ($this->getPcPhoto())
			{
				$photoPath = PHOTO_TEMP_PATH.'/'.uniqid('PPCO').'.jpg';
				
				//将图片保存到文件中
				imagejpeg($this->getPcPhoto() , $photoPath , 100);
			
				//将文件读取到字符串中
				$this->pcPhotoBinary = file_get_contents($photoPath);
				
				//删除文件
				unlink($photoPath);
			}
		}
			
		return $this->pcPhotoBinary;
	}
    /**
     * @return the $mobilePhotoBase64
     */
    public function getMobilePhotoBase64 ()
    {
    	if (!$this->mobilePhotoBase64)
		{		
			if ($this->mobilePhotoBinary)
			{
				$this->mobilePhotoBase64 = base64_encode($this->mobilePhotoBinary);
			}
			else if ($this->getMobilePhoto())
			{
				$photoPath = PHOTO_TEMP_PATH.'/'.uniqid('PMOB').'.jpg';
				
				//将图片保存到文件中
				imagejpeg($this->getMobilePhoto() , $photoPath , 100);
			
				//将文件读取到字符串中
				$this->mobilePhotoBinary = file_get_contents($photoPath);
				
				//删除文件
				unlink($photoPath);
				
				$this->mobilePhotoBase64 = base64_encode($this->mobilePhotoBinary);
			}
		}
			
		return $this->mobilePhotoBase64;
    }
	/**
	 * @return the $mobilePhotoBinary
	 */
	public function getMobilePhotoBinary() {

		if (!$this->mobilePhotoBinary)
		{
			if ($this->mobilePhotoBase64)
			{
				$this->mobilePhotoBinary = base64_decode($this->mobilePhotoBase64);
			}
			if ($this->getMobilePhoto())
			{
				$photoPath = PHOTO_TEMP_PATH.'/'.uniqid('PPCO').'.jpg';
				
				//将图片保存到文件中
				imagejpeg($this->getMobilePhoto() , $photoPath , 100);
			
				//将文件读取到字符串中
				$this->mobilePhotoBinary = file_get_contents($photoPath);
				
				//删除文件
				unlink($photoPath);
			}
		}
			
		return $this->mobilePhotoBinary;
	}
    /**
     * @return the $smallPhotoBase64
     */
    public function getSmallPhotoBase64 ()
    {
  	 	if (!$this->smallPhotoBase64)
		{
			if ($this->smallPhotoBinary)
			{
				$this->smallPhotoBase64 = base64_encode($this->smallPhotoBinary);
			}
			else if ($this->getSmallPhoto())
			{
				$photoPath = PHOTO_TEMP_PATH.'/'.uniqid('PSMA').'.jpg';
				
				//将图片保存到文件中
				imagejpeg($this->getSmallPhoto() , $photoPath , 100);
			
				//将文件读取到字符串中
				$this->smallPhotoBinary = file_get_contents($photoPath);
				
				//删除文件
				unlink($photoPath);
				
				$this->smallPhotoBase64 = base64_encode($this->smallPhotoBinary);
			}
		}
			
		return $this->smallPhotoBase64;
    }
	/**
	 * @return the $smallPhotoBinary
	 */
	public function getSmallPhotoBinary() {	
		if (!$this->smallPhotoBinary)
		{
			if ($this->smallPhotoBase64)
			{
				$this->smallPhotoBinary = base64_decode($this->smallPhotoBase64);
			}
			if ($this->getSmallPhoto())
			{
				$photoPath = PHOTO_TEMP_PATH.'/'.uniqid('PPCO').'.jpg';
				
				//将图片保存到文件中
				imagejpeg($this->getSmallPhoto() , $photoPath , 100);
			
				//将文件读取到字符串中
				$this->smallPhotoBinary = file_get_contents($photoPath);
				
				//删除文件
				unlink($photoPath);
			}
		}
			
		return $this->smallPhotoBinary;
	}

	/**
	 * 
	 * 在图片上添加LOGO
	 * @param unknown_type $text
	 * @param unknown_type $fontFile
	 * @param unknown_type $size
	 * @param unknown_type $angle
	 * @throws Exception
	 */
	public function addText($text , $fontFile , $color, $corner = self::RIGHTBOTTOM, $size = 10 , $angle = 0)
	{
		if (!is_file($fontFile))
		{
			throw new Exception('there is no font file exists');
			return false;
		}
		$photo = $this->getPhoto();
		if (!$photo)
		{
			throw new Exception('there is no photo source exists');
			return false;
		}
		//获得颜色对象
		$colorObj = imagecolorallocate($photo, $color[0] , $color[1] , $color[2]);
		
		//获得绘制字体的高度与宽度
		$textBox = imagettfbbox($size, $angle, $fontFile, $text);
		$textBox_width = $textBox[2]-$textBox[0];
		$textBox_height = $textBox[1]-$textBox[7];

		$textPosX = null;
		$textPosY = null;
		
		switch ($corner)
		{
			case self::LEFTTOP:
				$textPosX = 0;
				$textPosY = 0;
				break;
			case self::LEFTBOTTOM:
				$textPosX = 0;
				$textPosY = $this->getOriginHeight()-$textBox_height;
				break;
			case self::RIGHTTOP:
				$textPosX = $this->getOriginWidth()-$textBox_width;
				$textPosY = 0;
				break;
			case self::RIGHTBOTTOM:
				$textPosX = $this->getOriginWidth()-$textBox_width;
				$textPosY = $this->getOriginHeight()-$textBox_height;
				break;
		}
		
		imagettftext(
			$photo, 
			$size, 
			$angle, 
			$textPosX, 											//position x
			$textPosY, 											//position y
			$colorObj, 											//color
			$fontFile, 											//font file
			$text												//text	
			);
		
		$this->getPcPhoto(true);
		$this->getMobilePhoto(true);
		$this->getSmallPhoto(true);
	}
	
    /**
     * 
     * 根据大小限制、原图片大小，返回合理的大小
     * @param int $width		原宽度
     * @param int $height		原高度
     * @param int $widthLimit	宽度限制
     * @param int $heightLimit	高度限制
     * @param int $newWidth		新宽度
     * @param int $newHeight	新高度
     */
    public static function parseSize($width , $height , $widthLimit , $heightLimit , &$newWidth = null , &$newHeight = null)
    {
		$size = null;

		//如果宽度、高度都超出限制
		if ($width > $widthLimit && $height > $heightLimit)
		{
			$widZoomPercent = $width / $widthLimit;
			$heiZoomPercent = $height / $heightLimit;
			//如果宽度超出的比例较多，那么按照宽度的比例缩放
			if ($widZoomPercent > $heiZoomPercent)
			{
				$newWidth = $widthLimit;
				$newHeight = $height / $widZoomPercent;
			}
			//如果高度超出的比例较多，那么按照高度的比例缩放
			else 
			{
				$newWidth = $width / $heiZoomPercent;
				$newHeight = $heightLimit;
			}
		}
		else if ($width > $widthLimit)
		{
			$widZoomPercent = $width / $widthLimit;
			
			$newWidth = $widthLimit;
			$newHeight = $height / $widZoomPercent;
		}
		else if ($height > $heightLimit)
		{
			$heiZoomPercent = $height / $heightLimit;
			
			$newWidth = $width / $heiZoomPercent;
			$newHeight = $heightLimit;
		}
		else
		{
			$newWidth = $width;
			$newHeight = $height;
		}
		
		$size = array($newWidth , $newHeight);
		
		return $size;
    }
	public static function getFontDir($fileName)
	{
		return realpath(FONT_PATH.'/'.$fileName);
	}

	public function saveAsFile($floderPath , $fileName)
	{
		$path = null;
		$bSuccess = false;
		if ($this->photo)
		{
			$path = "$floderPath/$fileName.jpg";
			//保存图片
			$bSuccess = imagejpeg($this->photo, $path, 100);
		}
		if ($bSuccess)
		{
			return $path;
		}
		else
		{
			return null;
		}
	}

	/**
	 * 
	 * save the photo into temp floder
	 * @param string $photoId
	 * @param string $photoStr
	 * @param int 	$photoVersion	ORIGINAL_VERSION/PC_VERSION/MOBILE_VERSION/SMALL_VERSION
	 * @return string	web url of the avatar photo
	 */
	public static function saveToTemp($photoId, $photoStr, $photoVersion)
	{
		$photo = new Models_CommonAction_PPhoto($photoStr);
	
		//generate a unique file name base on avatar id
		$fileName = md5($photoVersion.'photo'.$photoId);
		$path = $photo->saveAsFile(PHOTO_TEMP_PATH, $fileName);
		
		$relativePath = Models_CommonAction_PUrl::convertAbsoToRela(PROJECT_PATH, $path);
		$photoUrl = PROJECTURL.str_replace("\\", "/", $relativePath);
    
		return $photoUrl;
	}
}
