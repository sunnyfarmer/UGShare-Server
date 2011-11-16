<?php
class Models_CommonAction_PDataJudge
{
    /**
     * 
     * 判断该字符串是否邮箱地址
     * @param string $str
     */
    public static function isAddress ($str)
    {
        if ($str) 
        {
            //正则表达式
            $regularExpression = '^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3}$';
            //如果php版本大于5.3则使用preg_match，并在正则表达式两侧加字符“/”标识表达式的开始与结束；否则使用ereg
            $result = version_compare(phpversion(), '5.3', '>') ? 
            		preg_match('/' . $regularExpression . '/', $str) : 
            		ereg($regularExpression, $str);
            if ($result)
                return true;
            else
                return false;
        } else {
            return false;
        }
    }
    /**
     * 
     * 判断该字符串是否电话号码
     * @param string $str
     */
    public static function isTelephone ($str)
    {
        if ($str) 
        {
            //正则表达式
            $regularExpression = '^[0-9][0-9-]{5,15}$';
            //如果php版本大于5.3则使用preg_match，并在正则表达式两侧加字符“/”标识表达式的开始与结束；否则使用ereg
            $result = version_compare(phpversion(), '5.3', '>') ? 
            		preg_match('/' . $regularExpression . '/', $str) : 
            		ereg($regularExpression, $str);
            if ($result)
                return true;
            else
                return false;
        } else 
        {
            return false;
        }
    }
    
    public static function isUrl($url)
    {
    	if ($url) 
        {
            //正则表达式
            $regularExpression = '[a-zA-z]+:+\/\/[^\s]*';
            //如果php版本大于5.3则使用preg_match，并在正则表达式两侧加字符“/”标识表达式的开始与结束；否则使用ereg
            $result = version_compare(phpversion(), '5.3', '>') ? 
            		preg_match('/' . $regularExpression . '/', $url) : 
            		ereg($regularExpression, $url);
            if ($result)
                return true;
            else
                return false;
        } else 
        {
            return false;
        }
    }
}