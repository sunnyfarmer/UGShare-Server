<?php
class Models_CommonAction_PString{

	private $str = null;
	
	/**
	 * 
	 * @param string $str
	 */
	public function __construct($str)
	{
		$this->str = $str;
	}
	

	/**
	 * 
	 * 根据参数左匹配符、右匹配符，得到成对的左匹配符与右匹配符在字符串中的位置
	 * @param string $str
	 * @param string $left
	 * @param string $right
	 * @param boolean $bSorted	是否按从小到大排序
	 * @return array	格式array(0=>array(leftPos , rightPos) , 1=>array(leftPos , rightPos),...)
	 */
	public static function matchSymbols($str , $left , $right , $bSorted = false)
	{
		$offsetArr = array();
		$isRuleBroken = false;
		
		$leftMatches = null;
		if ($str)
		{
			if (self::isSymbolInExpression($left))
				$left = "\\$left";
			preg_match_all("[$left]", $str, $leftMatches , PREG_OFFSET_CAPTURE);
		}
		
		$rightMatches = null;
		if ($str)
		{
			if (self::isSymbolInExpression($right))
				$right = "\\$right";
			preg_match_all("[$right]", $str, $rightMatches , PREG_OFFSET_CAPTURE);
		}
		
		$leftCount = count($leftMatches[0]);
		$rightCount = count($rightMatches[0]);
		
		if ($leftCount == $rightCount && $leftCount >= 1)
		{
			$leftTemp = array();
			$matchedCount = 0;
			
			array_push($leftTemp, $leftMatches[0][0][1]);
			
			$cot = 1;
			while ($matchedCount < $leftCount && !$isRuleBroken)
			{
				$tempSize = count($leftTemp);
				
				if ($cot < $leftCount)
				{
					$leftOffset = $leftMatches[0][$cot][1];
					$cot++;
					array_push($leftTemp, $leftOffset);
					$tempSize = count($leftTemp);
				}
				else 
				{
					$leftOffset = $leftTemp[$tempSize-1];
				}
				$rightOffset = $rightMatches[0][$matchedCount][1];
				
				if ($rightOffset <= $leftTemp[0])
				{
					$isRuleBroken = true;
					break;
				}
				if ($leftOffset > $rightOffset)
				{//这个左边符号索引大于右边符号索引，那么右边符号应该有匹配了
					for ($tempCot = $tempSize-2 ; $tempCot >= 0 ; $tempCot--)
					{
						if ($leftTemp[$tempCot] < $rightOffset)
						{
							array_push($offsetArr, array($leftTemp[$tempCot] , $rightOffset));
							$matchedCount++;
							array_splice($leftTemp, $tempCot , 1);
							break;
						}
					}
				}///there are problem here
				else if ($leftOffset < $rightOffset && $cot == $leftCount)//$tempSize == 1)
				{
					array_push($offsetArr, array($leftTemp[$tempSize-1] , $rightOffset));
					$matchedCount++;
					array_splice($leftTemp, $tempSize-1 , 1);
				}
			}	
		}
		
		if ($bSorted)
		{
			$offsetArr = self::sortOffsetArr($offsetArr);
		}
		return $offsetArr;
	}	

	/**
	 * 
	 * 根据参数左匹配符、右匹配符，得到成对的左匹配符与右匹配符在字符串中的位置（忽略$excludeStr之间的字符串）
	 * @param string $str
	 * @param string $left
	 * @param string $right
	 * @param boolean $bSorted
	 * @param string $excludeStr	匹配时，两个$excludeStr之间的字符串会被忽略
	 * @throws Exception
	 * @see Models_CommonAction_PString::matchSymbols
	 */
	public static function matchSymbolsExcluded($str , $left ,$right , $bSorted = false, $excludeStr = '"')
	{
		$matches = null;
		$offsetArr = null;
		
		preg_match_all("[$excludeStr]", $str, $matches , PREG_OFFSET_CAPTURE);
		
		$matchSize = count($matches[0]);
		if ($matchSize%2 != 0)
		{
			throw new Exception("script is formatted wrongly,include excess character '\"'\n脚本格式不正确，包含了多余的符号'\"'");
			return null;
		}
		
		if ($matchSize >= 2)
		{
			//删除特殊字符串之间的字符串
			$tempScript = $str;
			$byteArr = str_split($tempScript);
			$deleteLen = 0;
			for ($cot = 0 ; $cot < $matchSize ; $cot+=2)
			{
				$exOffset = $matches[0][$cot][1]-$deleteLen;
				$exLen = $matches[0][$cot+1][1]-$matches[0][$cot][1]+1;
				$deleteLen += $exLen;
				array_splice($byteArr, $exOffset , $exLen);
			}
			
			$tempScript = implode(NULL, $byteArr);

			//匹配搜索，得到对应的索引
			$offsetArr = self::matchSymbols($tempScript , $left, $right , $bSorted);
		
			//对截断字符串字符匹配搜索后，修改为完整字符串的索引
			foreach ($offsetArr as &$offset)
			{
				for($index = 0; $index<2 ; $index++)
				{	
					$cot = 0;
					$plusLen = $matches[0][$cot+1][1]-$matches[0][$cot][1]+1;
					$isLast = false;
					while ($offset[$index]+$plusLen > $matches[0][$cot+1][1])
					{
						if ($cot+2 >= $matchSize)
						{
							$isLast = true;
							break;
						}
						
						$cot += 2;
						$plusLen += $matches[0][$cot+1][1]-$matches[0][$cot][1]+1;
					}					
					if (!$isLast)
					{
						$plusLen -= $matches[0][$cot+1][1]-$matches[0][$cot][1]+1;
					}
					
					$offset[$index] += $plusLen;
				}
			}
		}
		else
		{
			$offsetArr = self::matchSymbols($str, $left, $right , $bSorted);
		}
		
		return $offsetArr;
	}
	/**
	 * 
	 * 字符是否正则表达式中的特殊字符
	 * @param string $str
	 */
	private static function isSymbolInExpression($str)
	{
		if ($str === '^' ||
			$str === '$' ||
			$str === '(' ||
			$str === ')' ||
			$str === '[' ||
			$str === ']' ||
			$str === '{' ||
			$str === '}' ||
			$str === '.' ||
			$str === '?' ||
			$str === '+' ||
			$str === '*' ||
			$str === '|')
		{
			return true;
		}
		else 
		{
			return false;
		}
	
	}
	/**
	 * 
	 * 对数组进行排序
	 * @param array $arr
	 */
	private static function sortOffsetArr($arr)
	{
		$leftOffsetArr = array();
		foreach ($arr as $offset)
		{
			$leftOffset = $offset[0];
			array_push($leftOffsetArr, $leftOffset);
		}
		
		//对左边符号索引进行排序
		asort($leftOffsetArr);
		
		$newOffsetArr = array();
		foreach ($leftOffsetArr as $key=>$value)
		{
			array_push($newOffsetArr, $arr[$key]);
		}
		
		return $newOffsetArr;
	}

	/**
	 * 
	 * 开头的字符与$needle匹配，则返回被截掉开头的字符串；否则，直接返回
	 * @param string $str
	 * @param string $needle
	 */
	public static function ltrimOnce($str , $needle)
	{
		$strLen = strlen($str);
		$needleLen = strlen($needle);
		
		if ($strLen < $needleLen)
			return $str;
		
		if ($needle === substr($str,0, $needleLen))
		{
			return substr($str, $needleLen);
		}
		else
		{
			return $str;
		}
	}
	
	/**
	 * 
	 * 尾部的字符与$needle匹配，则返回被截掉尾部的字符串；否则，直接返回
	 * @param string $str
	 * @param string $needle
	 */
	public static function rtrimOnce($str , $needle)
	{
		$strLen = strlen($str);
		$needleLen = strlen($needle);
		
		if ($strLen < $needleLen)
			return $str;
		
		$needleBeginPos = $strLen-$needleLen;
		
		if ($needle === substr($str, $needleBeginPos))
		{
			return substr($str, 0 , $needleBeginPos);
		}
		else
		{
			return $str;
		}
	}


	/**
	 * 
	 * 替换字符串（忽略$excludeStr之间的字符串）
	 * @param string $str
	 * @param string $search
	 * @param string $replacement
	 * @param string $excludeStr
	 */
	public static function replaceExclude($str , $search , $replacement , $excludeStr = "\"")
	{
		$strArr = explode($excludeStr, $str);
	
		$strArrSize = count($strArr);
	
		for($cot =0 ; $cot < $strArrSize ; $cot++)
		{
			if ($cot%2 === 0)
			{
				$strArr[$cot] = str_replace($search, $replacement, $strArr[$cot]);
			}	
		}
		
		return implode($excludeStr, $strArr);
	}
}


