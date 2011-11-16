<?php
/**
 * 1. GBK (GB2312/GB18030)
 * \x00-\xff  GBK双字节编码范围
 * \x20-\x7f  ASCII
 * \xa1-\xff  中文
 * \x80-\xff  中文
 * 
 * 2. UTF-8 (Unicode)
 * \u4e00-\u9fa5 (中文)
 * \x3130-\x318F (韩文
 * \xAC00-\xD7A3 (韩文)
 * \u0800-\u4e00 (日文)
 * ps: 韩文是大于[\u9fa5]的字符
 * 
 * 
 * 正则例子:
 * preg_replace("/([\x80-\xff])/","",$str);
 * preg_replace("/([u4e00-u9fa5])/","",$str);
 * 
 */
class Models_CommonAction_PCharset
{
    //	//判断内容里有没有中文-GBK (PHP)
    //	public static function hasGBKChinese($s){
    //    	return preg_match('/[\x80-\xff]./', $s);
    //	}
    //	
    //	public static function getGB2312String($str)
    //	{
    //	
    //	}
    //	
    //	public static function getUTF8String($str)
    //	{
    //	
    //	}
    /**
     * 
     * 分割中、英文混杂的字符串
     * @param string $str
     * @return array	
     */
    public static function str_split_utf8 ($str)
    {
        $split = 1;
        $array = array();
        for ($i = 0; $i < strlen($str);) {
            $value = ord($str[$i]);
            if ($value > 127) {
                if ($value >= 192 && $value <= 223)
                    $split = 2;
                elseif ($value >= 224 && $value <= 239)
                    $split = 3;
                elseif ($value >= 240 && $value <= 247)
                    $split = 4;
            } else {
                $split = 1;
            }
            $key = NULL;
            for ($j = 0; $j < $split; $j ++, $i ++) {
                $key .= $str[$i];
            }
            array_push($array, $key);
        }
        return $array;
    }
    /**
     * 
     * 将字符串转为unicode编码(只用于中文)
     * @param string $input		需要转换的字符串
     * @return	unicode编码的字符串
     */
    public static function unicode_encode ($input)
    { //str to Unicode
        $input = iconv('UTF-8', 'UCS-2', $input);
        $len = strlen($input);
        $str = '';
        for ($i = 0; $i < $len - 1; $i = $i + 2) {
            //每个中文由两个字节组成
            $c = $input[$i]; //取得第一个字节
            $c2 = $input[$i + 1]; //取得第二个字节 
            if (ord($c) > 0) { // 两个字节的字
                $cAnsiCode = base_convert(ord($c), 10, 16);
                $c2AnsiCode = base_convert(ord($c2), 10, 16);
                $bDigit1 = is_numeric($cAnsiCode); //判断取得的ANSII编码是否数字
                $bDigit2 = is_numeric($c2AnsiCode); //判断取得的ANSII编码是否数字
                $cAnsiCode = $bDigit1 ? sprintf('%02d', 
                $cAnsiCode) : $cAnsiCode;
                $c2AnsiCode = $bDigit2 ? sprintf('%02d', $c2AnsiCode) : $c2AnsiCode;
                $str .= '\\u' . $cAnsiCode . $c2AnsiCode;
            } else {
                $str .= $c2;
            }
        }
        $str = strtoupper($str);
        return $str;
    }
    /**
     * 
     * 将unicode的字符串转义为中文字符，保留中文字符以外的字符
     * @param string $input		需要转义的unicode字符串
     * @return	转义成功的中文字符
     */
    public static function unicodeDecodeKeepOtherChar ($input)
    { //Unicode to str
        $pattern = '/([\w]+)|(\\\u([\w]{4}))/i'; //匹配正则表达式
        //得到匹配的字符串数组
        preg_match_all($pattern, $input, $matches);
        if (! empty($matches)) {
            $sumCount = 0; //已经转义的字符数
            $matchCount = count($matches[0]); //能够匹配的字符数
            for ($cot = 0; $cot < $matchCount; $cot ++) {
                $str = $matches[0][$cot]; //单个unicode编码
                if (strpos($str, '\\u') === 0) {
                    $code = base_convert(substr($str, 2, 2), 16, 10);
                    $code2 = base_convert(substr($str, 4), 16, 10);
                    $c = chr($code) . chr($code2);
                    $c = iconv('UCS-2', 'UTF-8', $c);
                    $count = 0;
                    $input = str_replace($str, $c, $input, $count);
                    //累加已经转义的字符
                    $sumCount += $count;
                } else {
                    //do nothing
                }
                if ($sumCount >= $matchCount) {
                    break;
                }
            }
        }
        return $input;
    }
    /**
     * 
     * 将unicode的字符串转义为中文字符，不保留中文字符以外的字符
     * @param string $input		需要转义的unicode字符串
     * @return	转义成功的中文字符
     */
    public static function unicodeDecodeDeleteOtherChar ($input)
    { //Unicode to str
        $pattern = '/([\w]+)|(\\\u([\w]{4}))/i'; //匹配正则表达式
        //得到匹配的字符串数组
        preg_match_all($pattern, $input, $matches);
        if (! empty($matches)) {
            $input = '';
            $matchCount = count($matches[0]); //能够匹配的字符数
            for ($cot = 0; $cot < $matchCount; $cot ++) {
                $str = $matches[0][$cot]; //单个unicode编码
                if (strpos($str, '\\u') === 0) {
                    $code = base_convert(substr($str, 2, 2), 16, 10);
                    $code2 = base_convert(substr($str, 4), 16, 10);
                    $c = chr($code) . chr($code2);
                    $c = iconv('UCS-2', 'UTF-8', $c);
                    $input .= $c;
                } else {
                    $input .= $str;
                }
            }
        }
        return $input;
    }
}