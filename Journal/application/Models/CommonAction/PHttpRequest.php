<?php
class Models_CommonAction_PHttpRequest
{
	/**
	 * 
	 * do the Get request
	 * @param string $url
	 * @param string|struct $params
	 */
    public static function requestByGet ($url, $params)
    {
    	if (!Models_CommonAction_PDataJudge::isUrl($url))
    	{//如果url参数不合理，那么返回false
    		return false;
    	}
    	//设置get参数
    	$paramStr = '';
    	if ($params){
			foreach ($params as $id=>$value)
			{
				$paramStr .= "&$id=$value";
			}
	    	if (strpos($url, '?'))
	    	{//如果之前有参数
	    		$url .= $paramStr;	
	    	}
	    	else
	    	{
	    		$url .= '?'.ltrim($paramStr , '&');
	    	} 
       	}


        $result = null;
        
        $curl = curl_init();							// 初始化一个cURL对象

        curl_setopt($curl, CURLOPT_URL, $url);			// 设置您需要抓取的URL
        curl_setopt($curl, CURLOPT_HEADER, 1);			// 设置header
        
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);	// 设置cURL参数，要求结果保存到字符串中还是输出到屏幕上

        $result = curl_exec($curl);						// 运行cURL，请求网页

        curl_close($curl);        						// 关闭URL请求
        
        return $result;
    }
    public static function requestByPost ($url, $params)
    {
        if (!Models_CommonAction_PDataJudge::isUrl($url))
    	{//如果url参数不合理，那么返回false
    		return false;
    	}
    	
    	$paramStr = '';
    	if ($params)
    	{
    		foreach ($params as $id => $value)
    		{
    			$enValue = urlencode($value);
    			$paramStr .= "$id=$enValue&";
    		}
    		$paramStr = rtrim($paramStr , '&');
    	}
    	
        $result = null;
         
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		if ($paramStr)
		{	
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $paramStr);
		}
		$result = curl_exec($curl);
		curl_close($curl);
        
        print $result;
    }
}
