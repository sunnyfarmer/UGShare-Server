<?php
class Models_CommonAction_PDataCache
{
	const CACHEFILE_CAPACITY = 1000;		//每个缓存文件放置1000个用户的缓存
	
	public $cacheName = null;
	public $requestMethod = null;
	public $dataType = null;
	public $dataCot = null;
	public $dataArr = null;
	
	private static $fName = null;
	private static $fHandle = null;
	
	/**
	 * 
	 * @param string $cName					缓存的标识符
	 * @param string $rMethod				缓存对应的请求的方法
	 * @param string $dType					缓存对应的请求的数据类型
	 * @param int $dCot						缓存对应的请求的数据的数量	
	 * @param array $dArr					缓存对应的请求的数据
	 */
	public function __construct($cName , $rMethod , $dType , $dCot , $dArr)
	{
		$this->cacheName = $cName;
		$this->requestMethod = $rMethod;
		$this->dataType = $dType;
		$this->dataCot = $dCot;
		$this->dataArr = $dArr;
	}
	
	/**
	 * 
	 * 通过usrId获取缓存文件的名字
	 * @param string|integer $usrId
	 */
	public static function getCacheFileName($usrId)
	{
		$usrId = intval($usrId);
		
		$filename =(int)($usrId / self::CACHEFILE_CAPACITY);
		
		return $filename;
	}
	/**
	 * 
	 * 锁定文件资源，获取文件句柄
	 * @param string $fileName
	 * @param string $mode		
	 * 				r	只读。在文件的开头开始。
	 * 				r+	读/写。在文件的开头开始。
	 * 				w	只写。打开并清空文件的内容；如果文件不存在，则创建新文件。
	 *				w+	读/写。打开并清空文件的内容；如果文件不存在，则创建新文件。
	 * 				a	追加。打开并向文件文件的末端进行写操作，如果文件不存在，则创建新文件。
	 * 				a+	读/追加。通过向文件末端写内容，来保持文件内容。
	 * 				x	只写。创建新文件。如果文件以存在，则返回 FALSE。
	 * 				x+	读/写。创建新文件。如果文件已存在，则返回 FALSE 和一个错误。
	 * @return boolean 
	 */
	private static function openFile($fileName , $mode)
	{
		if (self::$fName != $fileName)
		{//如果原先已经有读入的文件资源，则先释放资源
			self::closeFile();
			self::$fName = $fileName;
			self::$fHandle = fopen(self::$fName, $mode);
		}
		if (self::$fHandle)
			return true;
		else 
			return false;
	}
	/**
	 * 
	 * 释放已经锁定的文件资源
	 */
	public  static function closeFile()
	{
		if (self::$fHandle)
			fclose(self::$fHandle);
		self::$fName = null;	
	}
	/**
	 * 
	 * 对字符串进行解析，获得PDataCache对象
	 * @param string $str			
	 * @param int $beginIndex		获取缓存数据的offset
	 * @param int $rowCount			获取缓存数据的数量
	 * @return	object|null			返回一个PDataCache对象，包含缓存的属性以及对应的数据
	 */
	private static function parseStr($str , $beginIndex , $rowCount)
	{
		//获得cacheName
		$offset = 0;
		$findPos = stripos($str, '|');
		$oneCacheName = substr($str, $offset, $findPos);
        $offset = $findPos + 1; //将offset设置为刚搜索到的字符位置
        //从上次搜索到的字符位置后开始搜索，获取请求类型
        $findPos = stripos($str, ':', $offset);
        $requestMethod = substr($str, $offset, $findPos - $offset);
        $offset = $findPos + 1;
        //从上次搜索到的字符位置后开始搜索，获取数据类型(游记或者动态等等)
        $findPos = stripos($str, ':', $offset);
        $dataType =substr($str, $offset , $findPos-$offset);
		$offset = $findPos+1;
				
		//从上次搜索到的字符位置后开始搜索，获取数据类型(游记或者动态等等)
		$findPos = stripos($str, ':' , $offset);
		$dataCot =substr($str, $offset , $findPos-$offset);
		$offset = $findPos+1;
				
		//从上次搜索到的字符位置后开始搜索，获取数据内容
		$endIndex = $beginIndex + $rowCount;
		if ($beginIndex <= $dataCot)
		{
			if ($endIndex > $dataCot)
			{
				$endIndex = $dataCot;
			}
			$dataArr = array();
			for ($cot = 0 ; $cot < $endIndex ; $cot++ )
			{
				$findPos = stripos($str, '*' , $offset);
				if (!$findPos)
				{//如果找不到下一个'*'号，那么这个数据是最后一个数据了，寻找下一个'；'号
					$findPos = stripos($str, ';' , $offset);
				}
				$data =substr($str, $offset , $findPos-$offset);
				$offset = $findPos+1;
				if ($cot >= $beginIndex)
					array_push($dataArr, $data);
			}
		}
		return new Models_CommonAction_PDataCache($oneCacheName, $requestMethod, $dataType, $dataCot, $dataArr);
	}
	/**
	 * 
	 * 对字符串进行解析，获取cache标识符
	 * @param string $str
	 * @return	string
	 */
	private static function parseGetCacheName($str)
	{
		$oneCacheName = null;
		//获得cacheName
		$offset = 0;
		$findPos = stripos($str, '|');
		if ($findPos)
	        $oneCacheName = substr($str, $offset, $findPos);
        
        return $oneCacheName;
	}
	/**
	 * 
	 * 对字符串进行解析，获取缓存数据的整个字符串
	 * @param string $str
	 * @return	string			
	 */
	private  static function parseGetCacheDataStr($str)
	{
		$len = strlen($str);
		
		$offset = 0;
		//第三个'：'号之后的的字符串为cacheData
		for ($cot = 0 ; $cot < 3 ; $cot ++)
		{
			//找到下一个'：'的存在
			$findPos = stripos($str, ':', $offset);
    	    $offset = $findPos + 1;
		}
				
		//从上次搜索到的字符位置后开始搜索，获取数据内容
		$dataStr =substr($str, $offset , $len-$findPos);
		$dataStr = rtrim(rtrim($dataStr , ' ') , ';');
		return $dataStr;
	}
	/**
	 * 
	 * 对缓存中属性以及数据进行修改
	 * @param string|int $usrId
	 * @param string $cacheName
	 * @param string $requestMethod
	 * @param string $dataType
	 * @param int $dataCot
	 * @param array $dataContent
	 * @return boolean 				修改缓存的状态,if success return true , else return false
	 */
	public static function modify($usrId , $cacheName , $requestMethod = null , $dataType = null , $dataCot = null , $dataContent = null)
	{
		//如果缓存数据，不是数组又不是空，那么返回false
		if (!is_array($dataContent) && !is_null($dataContent))
		{
			return false;
		}
		//统一dataCot与dataContent的数组大小
		if ($dataContent)
		{
			$dataCot = count($dataContent);
		}
		else
		{
			$dataCot = 0;
		}
		
		
		//获得缓存文件的文件名
		$path = CACHE_TEMP_PATH.'\\'.self::getCacheFileName($usrId);
		if (file_exists($path))
		{
			//首先搜索
			$strLine = null;
			$strArr = file($path);
			$strCot = count($strArr);
			for ($cot = 0 ; $cot < $strCot ; $cot++)
			{
				$strLine = $strArr[$cot];
				$oneCacheName = self::parseGetCacheName($strLine);
				if ($oneCacheName == $cacheName)
				{
					$dCache = self::parseStr($strLine, 0, 0);		
	
					$nCache = $cacheName;
					$nRequestMethod = ($requestMethod == null) ? $dCache->requestMethod : $requestMethod;
					$nDataType = ($dataType == null ) ? $dCache->dataType : $dataType;
					$nDataCot = ($dataCot == null) ? $dCache->dataCot : $dataCot;
					$nDataContentStr = null;
					if ($dataContent)
					{
						$hasContent = false;
						foreach ($dataContent as $data)
						{
							if ($hasContent)
							{
								$nDataContentStr .= "*";
							}
							$nDataContentStr .= "$data";
							$hasContent = true;
						}
					}
					else
					{
						$nDataContentStr = self::parseGetCacheDataStr($strLine);
					}
					
					$cacheStr = '';
					$cacheStr .= "$nCache|";
					$cacheStr .= "$nRequestMethod:";
					$cacheStr .= "$nDataType:";
					$cacheStr .= "$nDataCot:";
					$cacheStr .= "$nDataContentStr;\n";
								
					//将缓存写入到文件中
					$strArr[$cot] = $cacheStr;
					$fileContent = implode('', $strArr);
					file_put_contents($path, $fileContent);
				
					return true;
				}
			}
		}
		//如果搜索不到那么，创建一个这样的节点
		return self::insert($usrId , $cacheName, $requestMethod, $dataType, $dataCot, $dataContent);
	}
	/**
	 * 
	 * 无条件将缓存插入到缓存文件中
	 * @param string|int $usrId
	 * @param string $cacheName
	 * @param string $requestMethod
	 * @param string $dataType
	 * @param int $dataCot
	 * @param int $dataContent
	 * @return boolean				插入缓存的状态,if success return true , else return false
	 */
	public static function insert($usrId , $cacheName , $requestMethod , $dataType , $dataCot , $dataContent)
	{
		if (!$cacheName || !$requestMethod || !$dataType)
		{
			return false;
		}
		//如果缓存数据，不是数组又不是空，那么返回false
		if (!is_array($dataContent) && !is_null($dataContent))
		{
			return false;
		}
		//统一dataCot与dataContent的数组大小
		if ($dataContent)
		{
			$dataCot = count($dataContent);
		}
		else
		{
			$dataCot = 0;
		}
		
		//获得缓存文件的文件名
		$path = CACHE_TEMP_PATH.'\\'.self::getCacheFileName($usrId);
		
		//打开文件
		if (!self::openFile($path , 'a+'))
			return false;
			
		//组织缓存字符串
		$cacheStr = '';
		$cacheStr .= "$cacheName|";
		$cacheStr .= "$requestMethod:";
		$cacheStr .= "$dataType:";
		$cacheStr .= "$dataCot:";
		
		$hasContent = false;
		foreach ($dataContent as $data)
		{
			if ($hasContent)
			{
				$cacheStr .= "*";
			}
			$cacheStr .= "$data";
			$hasContent = true;
		}
		$cacheStr .= ";\n";
		//将缓存写入到文件中
		fwrite(self::$fHandle, $cacheStr);
		
		return true;
	}
	/**
	 * 
	 * 搜索缓存文件，获取PDataCache对象
	 * @param string|int $usrId
	 * @param string $cacheName
	 * @param int $beginIndex		获取缓存数据的offset
	 * @param int $rowCount			获取缓存数据的数量
	 * @return	Models_CommonAction_PDataCache|null			如果能够搜索到该缓存，则返回一个PDataCache对象，包含缓存的属性以及对应的数据
	 */
	public static function getCache($usrId , $cacheName , $beginIndex , $rowCount)
	{
		//获得缓存文件的文件名
		$path = CACHE_TEMP_PATH.'\\'.self::getCacheFileName($usrId);
		if (!self::openFile($path, 'r'))
		{
			return false;
		}
		
		$strLine = null;
		while (null != ($strLine = fgets(self::$fHandle)))
		{
			//获得cacheName
			$oneCacheName = self::parseGetCacheName($strLine);
            //如果cacheName匹配，那么逐个数据抽取
			if ($oneCacheName == $cacheName)
			{
				return self::parseStr($strLine, $beginIndex, $rowCount);
			}
		}
		return null;
	}
}