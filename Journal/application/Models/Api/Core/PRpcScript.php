<?php
class Models_Api_Core_PRpcScript
{
	const KW_IF = 'if';
	const KW_FOR = 'for';
	const KW_LASTRESULT = 'rs';
	
	const LG_AND = '&&';
	const LG_OR = '||';
	
	const CM_EQUAL = '==';
	const CM_EQUAL_STRICT = '===';
	const CM_NOT_EQUAL = '!=';
	const CM_NOT_EQUAL_STRICT = '!==';
	const CM_LESS = '<';
	const CM_MORE = '>';
	const CM_LESS_OR_EQUAL = '<=';
	const CM_MORE_OR_EQUAL = '>=';
	
	const SB_OBJECT	= '->';
	const SB_ARRAY_LEFT = '[';
	const SB_ARRAY_RIGHT = ']';
	
	const SB_TYPE_KW = 1;
	const SB_TYPE_LG = 2;
	const SB_TYPE_CM = 3;
	const SB_TYPE_SB = 4;
	
	public static $SB_TYPE_ARRAY = array(
		self::SB_TYPE_KW , 
		self::SB_TYPE_LG , 
		self::SB_TYPE_CM , 
		self::SB_TYPE_SB ,
	);
	
	public static $LG_ARRAY = array(
		self::LG_AND , 
		self::LG_OR
	);
	
	public static $CM_ARRAY = array(
		self::CM_EQUAL , 
		self::CM_EQUAL_STRICT , 
		self::CM_NOT_EQUAL , 
		self::CM_NOT_EQUAL_STRICT , 
		self::CM_LESS_OR_EQUAL , 
		self::CM_MORE_OR_EQUAL , 
		self::CM_LESS , 
		self::CM_MORE , 

	);
	
	public static  $KW_ARRAY = array(
		self::KW_IF , 
		self::KW_FOR , 
		self::KW_LASTRESULT , 
	);
	
	public static $SB_ARRAY = array(
		self::SB_OBJECT,
		self::SB_ARRAY_LEFT,
	);
	
	const LASTFORINDEX = '$n$';
	
	private $script = null;
	
	private $lastExecuteResult = null;
	private $lastForIndex = null;
	
	private $resultArr = array();
	
	public function __construct($s = null)
	{
		$this->script = $s;
	}
	/**
	 * 
	 * 执行脚本
	 */
	public function execute($scpt = null)
	{

		//TODO:添加函数调用的取值后，做加减乘除等运算


		if (!$scpt)
		{
			$scpt = $this->script;
		}
		else 
		{
			$this->setScript($scpt);
		}
		if ($scpt)
		{
			//delete脚本中的空格
			$scpt = Models_CommonAction_PString::replaceExclude($scpt, ' ', '');			
			
			//首先找出脚本第一个词是关键字or函数名
			$offset = strpos($scpt, '(');
			$cmdWord = substr($scpt, 0 , $offset);
			
			$endPos = 0;
			switch ($cmdWord)
			{
				case self::KW_IF:
					$offsetArr = Models_CommonAction_PString::matchSymbolsExcluded($scpt, '{', '}' , true,"\"");
					if (count($offsetArr)>0)
					{
						$endPos = $offsetArr[0][1];	
					
						$ifScript = substr($scpt, 0 , $endPos+1);
						
						$this->doIf($ifScript);
					}
					else 
					{
						throw new Zend_XmlRpc_Exception('if语句缺少花括号{ }');
						return false;
					}
					
					break;
				case self::KW_FOR:
					$offsetArr = Models_CommonAction_PString::matchSymbolsExcluded($scpt, '{', '}' , true , "\"");
					if (count($offsetArr)>0)
					{
						$endPos = $offsetArr[0][1];
						
						$forScript = substr($scpt, 0 , $endPos+1);
					
						$this->doFor($forScript);
					}
					else 
					{
						throw new Zend_XmlRpc_Exception('for语句缺少花括号{ }');
						return false;
					}
					break;
				default:
					$offsetArr = Models_CommonAction_PString::matchSymbolsExcluded($scpt, '(', ')' , true,"\"");
					if (count($offsetArr)>0)
					{
						$endPos = $offsetArr[0][1];
						
						$callScript = substr($scpt, 0 , $endPos+1);
						
						$this->lastExecuteResult = $this->executeMehod($callScript);
					
						array_push($this->resultArr, $this->lastExecuteResult);
					}
					else 
					{
						throw new Zend_XmlRpc_Exception('函数调用缺少括号()');
						return false;
					}
					break;
			}
			
			$restScript = substr($scpt, $endPos+1);
//			$restScript = ltrim($restScript);
			$restScript = Models_CommonAction_PString::ltrimOnce($restScript , ';');
			if ($restScript)
			{
				$this->execute($restScript);
			}
		}
		return $this->resultArr;
	}

	
	/**
	 * 
	 * 执行for循环的脚本
	 * @param string $script
	 */
	public function doFor($script)
	{
//		$script = ltrim($script);
		$script = Models_CommonAction_PString::ltrimOnce($script , self::KW_FOR);
//		$script = ltrim($script);
		
		$offsetArr = Models_CommonAction_PString::matchSymbolsExcluded($script, '(', ')' , true , "\"");

		if (!isset($offsetArr[0][0]) || !isset($offsetArr[0][1]))
		{
			throw new Zend_XmlRpc_Exception('for脚本语法不正确');
			return false;
		}
		
		$leftParIndex = $offsetArr[0][0];
		$rightParIndex = $offsetArr[0][1];
		
		$forIndexScript = substr($script, $leftParIndex+1 , $rightParIndex-$leftParIndex-1);
		$indexs = $this->valueIndexs($forIndexScript);
		
		//获得for内的运行脚本
		$forRunScript = substr($script, $rightParIndex+1);
//		$forRunScript = rtrim($forRunScript);
		$forRunScript = Models_CommonAction_PString::rtrimOnce($forRunScript , '}');
//		$forRunScript = rtrim($forRunScript);
//		$forRunScript = ltrim($forRunScript);
		$forRunScript = Models_CommonAction_PString::ltrimOnce($forRunScript , '{');
//		$forRunScript = ltrim($forRunScript);
		
		$beginIndex = $indexs[0];
		$endIndex = $indexs[1];
		for ($cot = $beginIndex ; $cot <= $endIndex ; $cot++)
		{
			$this->lastForIndex = $cot;
			
			$this->execute($forRunScript);
		}

	}
	
	private function valueIndexs($script)
	{
		$result = null;
		
		$bRulesBroken = false;
		$tempS = $script;
		
		$offsetArr = Models_CommonAction_PString::matchSymbolsExcluded($tempS, '(', ')', true , "\"");
	
		if (isset($offsetArr[0][0]) && isset($offsetArr[0][1]))
		{//有函数调用
			$beginIndex = null;
			$endIndex = null;
			
			$deliPos = strpos($tempS, ':' , $offsetArr[0][1]+1);
			
			if ($deliPos === false)
			{//左边为常量
				$deliPos = strpos($tempS, ':');
				$beginIndex = substr($tempS, 0 , $deliPos);
				
				$rightMethod = substr($tempS, $deliPos+1 , $offsetArr[0][1]-$deliPos);
				$rightValueStruct = substr($tempS, $offsetArr[0][1]+1);
				
				$rs = $this->executeMehod($rightMethod);
				$endIndex = $this->innerValue($rs, $rightValueStruct);
			}
			else 
			{//左边为函数调用
				$leftMethod = substr($tempS, 0 , $offsetArr[0][1]);
				$leftValueStruct = substr($tempS, $offsetArr[0][1]+1 , $deliPos-$offsetArr[0][1]-1);
				$rs = $this->executeMehod($leftMethod);
				$beginIndex = $this->innerValue($rs, $leftValueStruct);
				
				$tempS = substr($tempS, $deliPos+1);
				$rightOffsetArr = Models_CommonAction_PString::matchSymbolsExcluded($tempS, '(', ')' , true , "\"");

				if (isset($rightOffsetArr[0][0]) && isset($rightOffsetArr[0][1]))
				{//右边为函数调用
					$rightMethod = substr($tempS, 0 , $rightOffsetArr[0][1]+1);
					$rightValueStruct = substr($tempS, $rightOffsetArr[0][1]+1);
					$rs = $this->executeMehod($rightMethod);
					$endIndex = $this->innerValue($rs, $rightValueStruct);
				}
				else 
				{//右边为常量
					$endIndex = $tempS;	
				}
			}
			$result[0] = $beginIndex;
			$result[1] = $endIndex;
		}
		else 
		{//无函数调用
			$indexs = explode(':', $tempS);
			if (count($indexs) !== 2)
			{
				$bRulesBroken = true;
			}
			else if (!is_numeric($indexs[0]) || !is_numeric($indexs[1]))
			{
				$bRulesBroken = true;
			}
			$result[0] = $indexs[0];
			$result[1] = $indexs[1];
		}

		if ($bRulesBroken)
		{
			throw new Zend_XmlRpc_Exception("for脚本格式不对  $tempS");
			return false;
		}
		else 
		{		
//			foreach ($result as &$value)
//			{
//				$value = rtrim(ltrim($value));
//			}
			
			return $result;
		}
	}
	
	
	/**
	 * 
	 * 执行if语句的脚本
	 * @param string $script
	 */
	public function doIf($script)
	{
//		$script = ltrim($script);
		$script = Models_CommonAction_PString::ltrimOnce($script , self::KW_IF);
//		$script = ltrim($script);
		
		$bOperate = false;
		
		$offsetArr = Models_CommonAction_PString::matchSymbolsExcluded($script, '(', ')' , true , "\"");
		$conditionLeft = $offsetArr[0][0];
		$conditionRight = $offsetArr[0][1];
		if (isset($offsetArr[0][0]) && isset($offsetArr[0][1]))
		{
			$conditionScript = substr($script , $conditionLeft+1, $conditionRight-$offsetArr[0][0]-1);
		}
		if ($conditionScript)
		{
			$boolArr = array();
			$logicArr = array();
			
			//首先用逻辑运算符得出第一个条件的结束位置
			$tempS = $conditionScript;
			$next = self::nextSymbolPos($tempS, self::SB_TYPE_LG);
			
			while(true)
			{
				$nextPos = $next[0];
				$nextSymbol = $next[1];
				
				$len = $nextPos === -1 ? strlen($tempS) : $nextPos;
				$condition = substr($tempS, 0 , $len);

				if (!$condition)
				{
					break;
				}
				
				$bool = $this->valueCondition($condition);
				
				//将条件的运算值插入到$boolArr中
				array_push($boolArr, $bool);
				//如果存在逻辑运算符，那么插入到逻辑$logicArr中
				if ($nextSymbol)
				{
					array_push($logicArr, $nextSymbol);
				}
				else 
				{
					break;
				}
				
				//将前一个条件与逻辑运算符截掉
				if ($nextPos !== -1)
				{
					$tempS = substr($tempS, $nextPos+strlen($nextSymbol));
				}
				else 
				{
					$tempS = null;
				}
				$next = self::nextSymbolPos($tempS, self::SB_TYPE_LG);
			}
			
			$bOperate = $this->mergeBoolean($boolArr, $logicArr);
		}

		if ($bOperate)
		{
			$leftOperate = strpos($script, '{' , $conditionRight+1);
			$operateScript = substr($script, $leftOperate+1);
//			$operateScript = rtrim($operateScript);
			$operateScript = Models_CommonAction_PString::rtrimOnce($operateScript , '}');
			
			$this->execute($operateScript);
		}
	}

	/**
	 * 
	 * 进行boolean值的逻辑运算
	 * @param array $boolArr	array(boolean , boolean , boolean)
	 * @param array $logicArr	array(logicSB , logicSB);
	 * @return boolean
	 */
	private function mergeBoolean($boolArr , $logicArr)
	{
		$returnValue = null;
		
		//TODO:将数组中的boolean值合并
		$boolSize = count($boolArr);
		$logicSize = count($logicArr);
		if ($boolSize !== $logicSize+1 || $boolSize === 0) {
			throw new Zend_XmlRpc_Exception('逻辑运算脚本中不符合逻辑运算的规则');
		}
		
		$returnValue = $boolArr[0];
		for ($cot = 0 ; $cot < $logicSize ; $cot++)
		{
			$logicSb = $logicArr[$cot];
			$boolValue = $boolArr[$cot+1];
			
			switch ($logicSb)
			{
				case self::LG_AND:
					$returnValue = ($returnValue && $boolValue);
					break;
				case self::LG_OR:
					$returnValue = ($returnValue || $boolValue);
					break;
				default:
					throw new Zend_XmlRpc_Exception("逻辑运算脚本中的逻辑运算符 $logicSb 不存在");
					break;
			}
		}
		
		return $returnValue;
	}

	/**
	 * 
	 * 根据比较运算符字符串，进行值比较
	 * @param boolean|int|float|double|long $left
	 * @param boolean|int|float|double|long $right
	 * @param string $cmSymbol
	 * @throws Zend_XmlRpc_Exception	不存在$cmSymbol比较运算符时，抛出异常
	 */
	private function compare($left , $right , $cmSymbol)
	{
		$result = null;
		switch ($cmSymbol)
		{
			case self::CM_EQUAL:
				$result = ($left == $right);
				break;
			case self::CM_EQUAL_STRICT:
				$result = ($left === $right);
				break;
			case self::CM_NOT_EQUAL:
				$result = ($left != $right);
				break;
			case self::CM_NOT_EQUAL_STRICT:
				$result = ($left !== $right);
				break;
			case self::CM_LESS:
				$result = ($left < $right);
				break;
			case self::CM_MORE:
				$result = ($left > $right);
				break;
			case self::CM_LESS_OR_EQUAL:
				$result = ($left <= $right);
				break;
			case self::CM_MORE_OR_EQUAL:
				$result = ($left >= $right);
				break;
			default:
				throw new Zend_XmlRpc_Exception('不存在对应的比较运算符');
				break;
		}
		return $result;
	}
	
	/**
	 * 
	 * 根据条件脚本，得出boolean值
	 * @param string $conditon
	 * @return boolean
	 */
	private function valueCondition($condition)
	{
		$offsetArr = Models_CommonAction_PString::matchSymbolsExcluded($condition, '(', ')' , true , "\"");
		if (!isset($offsetArr[0][0]) || !isset($offsetArr[0][1]))
		{//如果左边没有函数调用，那么抛出异常
			throw new Zend_XmlRpc_Exception('没有函数调用');
			return false;
		}
		
		//获得左边的函数调用语句
		$leftMethod = substr($condition, 0, $offsetArr[0][1]+1);
		$leftValueStruct = null;
		$leftValue = null;
		//获得函数调用语句以外的部分
		$temp = substr($condition, $offsetArr[0][1]+1);
		
		$cmSBPos = self::nextSymbolPos($temp, self::SB_TYPE_CM);
		if ($cmSBPos[0] === -1)
		{//如果没有比较运算符，那么直接执行函数调用并返回
			$leftValueStruct = $temp;
			
			$rs = $this->executeMehod($leftMethod);
			$leftValue = $this->innerValue($rs, $leftValueStruct);
			return $leftValue;
		}
		else 
		{//有比较运算符
			$cmSb = $cmSBPos[1];
			
			$leftValueStruct = substr($temp, 0, $cmSBPos[0]);
			
			$rs = $this->executeMehod($leftMethod);
			$leftValue = $this->innerValue($rs, $leftValueStruct);
		
			//得到右边的值或函数调用脚本
			$temp = substr($temp, $cmSBPos[0]+strlen($cmSBPos[1]));
//			$temp = ltrim($temp);
			
			$rightMethod = null;
			$rightValueStruct = null;
			$rightValue = null;
			if (is_numeric($temp))
			{//数字类型的数据
				$rightValue = $temp;		
			}
			else 
			{
				if (substr($temp, 0 , 1) === "\"")
				{//字符串或者时间类型的数据 
					$rightValue = Models_CommonAction_PString::ltrimOnce( Models_CommonAction_PString::rtrimOnce($temp,"\"") , "\"");
				}
				else 
				{//函数调用类型
					$rightOffsetArr = Models_CommonAction_PString::matchSymbolsExcluded($temp, '(', ')' , true , "\"");
					
					if (isset($rightOffsetArr[0][0]) && isset($rightOffsetArr[0][1]))
					{
						$rightMethod = substr($temp, 0 , $rightOffsetArr[0][1]+1);
						$rightValueStruct = substr($temp, $rightOffsetArr[0][1]+1);
						
						$rs = $this->executeMehod($rightMethod);
						$rightValue = $this->innerValue($rs, $rightValueStruct);
					}
					else 
					{
						throw new Zend_XmlRpc_Exception('比较条件语句中出现非数字、非字符串、非时间、非函数调用的脚本');
					}
				}
			}
			
			return $this->compare($leftValue, $rightValue, $cmSb);			
		}
	}

	
	/**
	 * 
	 * 执行调用函数的脚本
	 * @param string $script
	 * @throws Zend_XmlRpc_Exception
	 */
	public function executeMehod($script)
	{
		$namespaceEndPos = strpos($script, '.');
		if (false === $namespaceEndPos)
		{
			throw new Zend_XmlRpc_Exception('函数没有名字域');
			return false;
		}
		$namespace = substr($script, 0,$namespaceEndPos);
		
		$methodNameEndPos = strpos($script, '(');
		if (false === $methodNameEndPos)
		{
			throw new Zend_XmlRpc_Exception('没有函数名');
			return false;
		}
		$methodName = substr($script, $namespaceEndPos+1 , $methodNameEndPos-$namespaceEndPos-1);
	
		$parameter = substr($script, $methodNameEndPos+1);
		$parameter = Models_CommonAction_PString::rtrimOnce($parameter,')');
		
		$paraArray = self::paraStrToArray($parameter);

		$paraValueArray = self::paraStrArrToValueArr($paraArray);
		
		return self::call("$namespace.$methodName", $paraValueArray);
	}
	/**
	 * 
	 * 执行方法
	 * @param string $func	格式如namespace.funcName
	 * @param array $params	
	 * @throws Zend_XmlRpc_Exception	无法查找到对应的方法，则抛出异常
	 */
	public static function call($func , $params)
	{
		$funcFactor = explode('.', $func);
		$namespace = $funcFactor[0];
		$funcName = $funcFactor[1];
		
		//首先查找rpc中是否有该namespace的注册
		if (key_exists($namespace, Models_Api_PCommon::$interfaceClass))
		{
			foreach (Models_Api_PCommon::$interfaceClass[$namespace] as $className)
			{
				if(method_exists($className, $funcName))
				{
					return call_user_func_array(array($className , $funcName), $params);
				}
			}
		}
		
		throw new Zend_XmlRpc_Exception('没有找到相应的方法'.$func);
		return false;
	}
	/**
	 * 
	 * 获得下一个特殊字符的位置(经测试，通过 	by sunnyfarmer 2011.08.11)
	 * @param string $str
	 */
	public static function nextSymbolPos($str , $type)
	{
		$sbArr = null;
		switch ($type)
		{
			case self::SB_TYPE_KW:
				$sbArr = self::$KW_ARRAY;
				break;
			case self::SB_TYPE_CM:
				$sbArr = self::$CM_ARRAY;
				break;
			case self::SB_TYPE_LG:
				$sbArr = self::$LG_ARRAY;
				break;
			case self::SB_TYPE_SB:
				$sbArr = self::$SB_ARRAY;
				break;
		}
		
		$pos = -1;
		$symbol = null;
		foreach ($sbArr as $sb)
		{
			$candidatePos = strpos($str, $sb);
			if ($candidatePos !== false)
			{	
				if ($pos > $candidatePos || 0 > $pos)
				{
					$pos = $candidatePos;
					$symbol = $sb;
				}
			}
		}
		
		return array($pos , $symbol);
	}
	
	/**
	 * 
	 * 根据字符串，获得数组内的值(经测试，通过 	by sunnyfarmer 2011.08.11)
	 * @param array $rs
	 * @param string $str
	 */
	public static function innerValue($rs , $str)
	{
		$value = $rs;

		if (!$str){
			return $rs;
		}
		//循环判断字符串的内容，获得值
		$tempS = $str;
		
		$bRulsBroken = false;
		do
		{
			//获得第一个取值符号
			$next = self::nextSymbolPos($tempS , self::SB_TYPE_SB);
			$nextPos = $next[0];
			$nextSym = $next[1];
			if ($nextPos !== 0)
			{
				$bRulsBroken = true;
				break;
			}
			
			//截掉第一个取值符号
			$tempS = substr($tempS, strlen($nextSym));
			
			//获得下一个取值符号的位置
			$nnext = self::nextSymbolPos($tempS , self::SB_TYPE_SB);
			$nnextPos = $nnext[0];
			$nnextSym = $nnext[1];

			if (-1 !== $nnextPos)
			{
				//获得取值的key
				$key = substr($tempS, 0 , $nnextPos);
				//截取key
				$tempS = substr($tempS, $nnextPos);
			}
			else 
			{
				$key = $tempS;
				$tempS = null;
			}
			if ($key === '$n$')
			{
				$key = $this->lastForIndex;
			}
			switch ($nextSym)
			{
				case self::SB_OBJECT:
					if (isset($value->$key))
					{
						$value = $value->$key;
					}
					else 
					{
						$bRulsBroken = true;
					}
					break;
				case self::SB_ARRAY_LEFT:
					$key = Models_CommonAction_PString::rtrimOnce($key , ']');
					if (isset($value[$key]))
					{
						$value = $value[$key];
					}
					else 
					{
						$bRulsBroken = true;
					}
					break;
				default:
					$bRulsBroken = true;
					break;
			}
			if ($bRulsBroken)
				break;
		}while ($tempS);
		
		if ($bRulsBroken)
		{
			throw new Zend_XmlRpc_Exception('获取结果集value的脚本没有取值符号"->"或"[]"');
		}
		
		return $value;
	}
	
	/**
	 * 
	 * 执行嵌套的函数，得到实参
	 * @param array $paraArr
	 */
	public function paraStrArrToValueArr($paraArr)
	{
		$valueArr = array();
		
		foreach ($paraArr as &$para)
		{
			$value = null;
			if (!is_numeric($para))
			{
				if (substr($para, 0 , 1) === "\"")
				{//字符串或者时间参数
					$value = Models_CommonAction_PString::rtrimOnce(Models_CommonAction_PString::ltrimOnce($para , "\"") , "\"");
				}
				else 
				{//函数调用
					$leftParPos = strpos($para, '(');
					$rightParPos = strpos($para, ')');
					if ($leftParPos !== false && $rightParPos !== false)
					{
						$callScript = substr($para , 0 , $rightParPos+1);
						$rs = self::executeMehod($callScript);
						$valueStr = substr($para , $rightParPos+1);
						
						$value = self::innerValue($rs, $valueStr);
					}
					else if (substr($para, 0 , 2) === self::KW_LASTRESULT)
					{
						if ($this->lastExecuteResult)
						{
							$rs = $this->lastExecuteResult;
							$valueStr = substr($para , 2);
							$value = self::innerValue($rs, $valueStr);
						}
					}
					else 
					{
						throw new Zend_XmlRpc_Exception('参数不符合规格，不是数字、字符串、时间，也不是函数执行或者上一个执行函数的返回值');
						return false;
					}
				}
			}
			else
			{
				$value = $para;
			}
			array_push($valueArr, $value);
		}

		return $valueArr;
	}
	
	/**
	 * 
	 * 将参数的字符串转化为参数数组
	 * @param string $paraStr
	 * @throws Zend_XmlRpc_Exception	参数字符串不符合规格时，抛出异常
	 */
	public static function paraStrToArray($paraStr)
	{
		$paraArray = explode(',', $paraStr);
		$splitSize = count($paraArray);
		for ($cot = 0 ; $cot < $splitSize ; $cot++)
		{
			$para = $paraArray[$cot];
			$leftParPos = strpos($para, '(');
			$leftQuotePos = strpos($para, "\"");
			if (false === $leftParPos && false === $leftQuotePos)
			{
				continue;
			}
			elseif (false !== $leftParPos && false === $leftQuotePos)
			{//有括号，无双引号
				$leftIndex = $cot;
				$rightIndex = $cot;
				$bMatched = false;
				while (true)
				{
					$rightParPos = strpos($paraArray[$rightIndex], ')' , $leftParPos+1);
					if (false !== $rightParPos)
					{
						$bMatched = true;
						break;
					}
					$rightIndex++;
					if ($rightIndex >= $splitSize)
					{
						break;
					}
				}
				if ($bMatched)
				{
					if ($leftIndex != $rightIndex)
					{
						//合并
						for($arrCot = $leftIndex+1 ; $arrCot <= $rightIndex ; $arrCot++)
						{
							$paraArray[$leftIndex] .= $paraArray[$arrCot];
						}
						array_splice($paraArray, $leftIndex+1 , $rightIndex-$leftIndex);
						//减掉splitSize
						$splitSize = count($paraArray);
					}
				}
				else
				{
					throw new Zend_XmlRpc_Exception("参数中无法为'('找到匹配的')'");
				}
			}
			elseif (false === $leftParPos && false !== $leftQuotePos)
			{//无括号，有双引号
				$leftIndex = $cot;
				$rightIndex = $cot;
				$bMatched = false;
				while (true)
				{
					$rightQuotePos = strpos($paraArray[$rightIndex], '"' , $leftQuotePos+1);
					if (false !== $rightQuotePos)
					{
						$bMatched = true;
						break;
					}
					$rightIndex++;
					if ($rightIndex >= $splitSize)
					{
						break;
					}
				}
				if ($bMatched)
				{
					if ($leftIndex != $rightIndex)
					{
						//合并
						for($arrCot = $leftIndex+1 ; $arrCot <= $rightIndex ; $arrCot++)
						{
							$paraArray[$leftIndex] .= $paraArray[$arrCot];
						}
						array_splice($paraArray, $leftIndex+1 , $rightIndex-$leftIndex);
						//减掉splitSize
						$splitSize = count($paraArray);
					}
				}
				else
				{
					throw new Zend_XmlRpc_Exception("参数中无法为'\"'找到匹配的'\"'");
				}
			}
			else 
			{//有括号，也有双引号
				if ($leftParPos < $leftQuotePos)
				{
					//先找到右边双引号，在找到右边括号
					$leftIndex = $cot;
					$rightIndex = $cot;
					$bQuoteMatched = false;
					$bParMatched = false;
					while (true)
					{
						$rightQuotePos = strpos($paraArray[$rightIndex], "\"" , $leftQuotePos+1);
						if (false !== $rightQuotePos)
						{
							$bQuoteMatched = true;
							break;
						}
						$rightIndex++;
						if ($rightIndex >= $splitSize)
						{
							break;
						}
					}
					if ($rightIndex < $splitSize)
					{
						while (true)
						{
							$rightParPos = strpos($paraArray[$rightIndex], ')' , $leftParPos+1);
							if (false !== $rightParPos)
							{
								$bParMatched = true;
								break;
							}
							$rightIndex++;
							if ($rightIndex >= $splitSize)
							{
								break;
							}
						}
					}
					if ($bParMatched && $bQuoteMatched)
					{	
						//合并
						for($arrCot = $leftIndex+1 ; $arrCot <= $rightIndex ; $arrCot++)
						{
							$paraArray[$leftIndex] .= $paraArray[$arrCot];
						}
						array_splice($paraArray, $leftIndex+1 , $rightIndex-$leftIndex);
						//减掉splitSize
						$splitSize = count($paraArray);				
					}
					else
					{
						throw new Zend_XmlRpc_Exception("参数中无法为'\"'、'('找到匹配的'\"'或')'");
					}
				}
				else 
				{
					//找到右边双引号即可
					$leftIndex = $cot;
					$rightIndex = $cot;
					$bMatched = false;
					while (true)
					{
						$rightQuotePos = strpos($paraArray[$rightIndex], '"' , $leftQuotePos+1);
						if (false !== $rightQuotePos)
						{
							$bMatched = true;
							break;
						}
						$rightIndex++;
						if ($rightIndex >= $splitSize)
						{
							break;
						}
					}
					if ($bMatched)
					{
						if ($leftIndex != $rightIndex)
						{
							//合并
							for($arrCot = $leftIndex+1 ; $arrCot <= $rightIndex ; $arrCot++)
							{
								$paraArray[$leftIndex] .= $paraArray[$arrCot];
							}
							array_splice($paraArray, $leftIndex+1 , $rightIndex-$leftIndex);
							//减掉splitSize
							$splitSize = count($paraArray);
						}
					}
					else
					{
						throw new Zend_XmlRpc_Exception("参数中无法为'\"'找到匹配的'\"'");
					}					
				}
			}
		}

		return $paraArray;
	}
	
	/**
	 * @return the $script
	 */
	public function getScript() {
		return $this->script;
	}

	/**
	 * @param field_type $script
	 */
	public function setScript($script) {
		$this->script = $script;
	}
	/**
	 * @return the $resultArr
	 */
	public function getResultArr() {
		return $this->resultArr;
	}

}