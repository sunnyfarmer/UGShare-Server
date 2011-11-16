<?php
abstract class Models_Behavior_PIBehavior
{
	//行为id
	var $behaviorID = null;
	//行为属性变量	
	var $propertys = null;
	//行为属性是否设置的标志
	var $propertysMark = null;
	/**
	 * 
	 * 数组形式：
	 * 	array(
	 * 		behavior1ID =>array(
	 *				property1ID=>property1Value,
	 *				property2ID=>property2Value
	 *		),
	 *		behavior2ID	=>array(
	 *				property1ID=>property1Value,
	 *				property2ID=>property2Value
	 *		)
	 * 	)
	 * @var array
	 */
	var $triggerBehaviors = null;

	
	function __construct($bhId)
	{
		$this->behaviorID = $bhId;
		if (null == $this->behaviorID)
		{
			throw new Models_Exception(Models_Core::ERR_BEHAVIOR_ID_NULL);
			return false;
		}
		$pids = Models_Behavior_PBehaviorEnum::getPropertyIds($this->behaviorID);
		foreach ($pids as $id)
		{
			$this->propertys[$id] = null;
			$this->propertysMark[$id] = false;
		}
	}
	
	/**
	 * 
	 * to do the action of the behavior
	 * @return integer
	 */
	protected abstract function todo();
	
	/**
	 * 
	 * 对所有条件进行检查
	 * @throws Exception
	 */
	private function check()
	{
		$returnBool = false;
		if (null == $this->behaviorID)
		{//如果行为id为空，那么抛出异常
			throw new Models_Exception(Models_Core::ERR_BEHAVIOR_ID_NULL);
			return $returnBool;
		}

		//清空需要触发的行为列表
		$this->clearTriggerBehavior();
	
		//获得该行为可能触发的所有behaviorLine
		$behaviorLines = Models_Behavior_PBehaviorEnum::getLineIds($this->behaviorID);
		if ($behaviorLines)
		{
			foreach ($behaviorLines as $lineId)
			{
				//检测每个line
				if( $this->checkSingle($lineId) )
				{
					$returnBool = true;			//如果有一个BehaviorLine符合条件，那么返回true	
				}
			}
		}
		return $returnBool;
	}
	/**
	 * 
	 * 对单个BehaviorLine进行检查
	 * @param string $behaviorLineID
	 * @throws Exception
	 */
	private function checkSingle($behaviorLineID)
	{
		$returnResult = false;
		//如果behaviorLine的id为空，那么抛出异常
		if (null == $behaviorLineID)
		{
			throw new Models_Exception(Models_Core::ERR_BEHAVIOR_LINE_ID_NULL);
			return $returnResult;
		}
		
		$net = simplexml_load_file(BEHAVIORNETXMLPATH);
		foreach ($net as $line)
		{
			//寻找合适的行为节点
			if (strval($line['id']) == $behaviorLineID)
			{
				$beginNode = $line->BeginNode[0];
				//循环ParameterValue
				$parameterIndex = 0;
				$parameterNode = $beginNode->ParameterValue[$parameterIndex];
				while($parameterNode)
				{
					//检查parameter是否符合条件，如果不符合，直接返回false
					$parameterID = strval($parameterNode['id']);
					$parameterValue = $this->propertys[$parameterID];
					$comparisionID = strval($parameterNode['comparisionID']);
					$comparisionObject = $parameterNode['comparisionObject'];
					$compareResult = Models_Datatype_PDatatypeManager::compare($comparisionID, $parameterValue, $comparisionObject);
					if(!$compareResult)
					{//如果不符合条件，直接返回false
						return false;
					}
					$parameterIndex++;
					$parameterNode = $beginNode->ParameterValue[$parameterIndex];
				}
				//循环conditions
				$conditionIndex = 0 ; 
				$conditionNode = $beginNode->Condition[$conditionIndex];
				while ($conditionNode)
				{
					//******************************
					//检查condition是否符合，如果不符合，直接返回false
					$conditionId = $conditionNode['conditionId'];
					$conditionClassName = Models_Condition_PConditionEnum::getConditionClassName(intval($conditionId));
					/**
					 * @var PCondition
					 */
					$conditionInstance = new $conditionClassName();
					if(!$conditionInstance->check(12))/***********userid*********/
					{//如果不符合条件，直接返回false
						return false;
					}
					$conditionIndex++;
					$conditionNode = $beginNode->PropertyValue[$conditionIndex];
				}				
				
				//符合条件，那么EndNode到触发行为数组$triggerBehaviors中
				$endNodeIndex = 0;
				$endNode = $line->EndNode[$endNodeIndex];

				while ($endNode)	
				{
					$returnResult = true;
					//将将每个EndNode的参数赋值
					$triggerBehaviorID = strval($endNode['id']);
					$triggerParameterArray = null;
					foreach ($endNode->children() as $triggerParameter)
					{
						$triggerParameterArray[ strval($triggerParameter['id']) ] = strval($triggerParameter['value']);
					}
					$this->triggerBehaviors[$triggerBehaviorID] = $triggerParameterArray;
					
					$endNodeIndex++;
					$endNode = $line->EndNode[$endNodeIndex];
				}
					
				break;
			}
		}
		
		return $returnResult;
	}

	private function trigger()
	{
		//propertys行为的属性数组，$behaviorID行为的id
		foreach ($this->triggerBehaviors as $behaviorID=>$propertys)
		{
			//获取行为的实例
			$className = Models_Behavior_PBehaviorEnum::getName($behaviorID);
			$behaviorInstance = Models_Behavior_PBehaviorManager::getInstance($className);
			//设置行为的参数
			$behaviorInstance->setPropertys($propertys);
			//触发行为
			$behaviorInstance->chase();
		}
	}
	
	/**
	 * 
	 * todo,check, trigger
	 * @param boolean $isCheck	if $isCheck is true，then check and trigger;oterwise, only todo the action
	 * @return integer
	 */
	function chase($isCheck = false)
	{
		//完成行为
		$state = $this->todo();
		
		if ($isCheck){
			//判断是否能够触发其他行为
			if ($this->check())
			{
				$this->trigger();
			}
		}
		return $state;
	}

	/**
	 * @return the $behaviorID
	 */
	public function getBehaviorID() {
		return $this->behaviorID;
	}

	/**
	 * @param field_type $behaviorID
	 */
	private function setBehaviorID($behaviorID) {
		$this->behaviorID = $behaviorID;
	}

	/**
	 * @return the $propertys
	 */
	public function getPropertys() {
		return $this->propertys;
	}
	/**
	 * 
	 * set the value for specific property
	 * @param string $propertyID
	 * @param string $propertyValue
	 */
	function setProperty($propertyID , $propertyValue)
	{
		$this->propertys[$propertyID] = $propertyValue;
		$this->propertysMark[$propertyID] = true;
	}
	/**
	 * @param field_type $propertys
	 */
	public function setPropertys($propertyArray) {
		foreach ($this->propertys as $id=>$value)
		{
			if(array_key_exists($id, $propertyArray))
			{
				$this->propertys[$id] = $propertyArray[$id];
				$this->propertysMark[$id] = true;
			}
			else 
			{
				throw new Models_Exception(Models_Core::ERR_BEHAVIOR_PROPERTY_WRONG_FORMAT);
				return false;
			}
		}
	}

	/**
	 * @return the $triggerBehaviors
	 */
	public function getTriggerBehaviors() {
		return $this->triggerBehaviors;
	}

	/**
	 * @param array $triggerBehaviors
	 */
	public function setTriggerBehaviors($triggerBehaviors) {
		$this->triggerBehaviors = $triggerBehaviors;
	}

	/**
	 * 
	 * 清空需要触发的行为列表
	 */
	public function clearTriggerBehavior()
	{
		$this->triggerBehaviors = null;
	}
	
	/**
	 * 
	 * 重置行为属性数组
	 * @throws Models_Exception	当行为的属性与属性属性标志，key不一致的时候，抛出异常
	 */
	public function resetPropertys()
	{
		foreach ($this->propertys as $id => $value)
		{
			if(!array_key_exists($id, $this->propertysMark))
			{//如果在属性标志数组没有这个属性的标志记录，那么抛出异常
				throw new Models_Exception(Models_Core::ERR_BEHAVIOR_PROPERTY_MARK_WRONG_FORMAT);
				return false;
			}
			$this->propertys[$id] = null;
			$this->propertysMark[$id] = false;
		}
		return true;
	}
	
	/**
	 * 
	 * 行为属性是否已经设置
	 * 	已经设置，返回true；否则，返回false
	 * @param string $propertyId	为null时，若所有属性都已设置，返回true；否则，返回false
	 */
	public function isPropertySet($propertyId = null)
	{
		if (!$propertyId)
		{//propertyId为空时
			foreach ($this->propertysMark as $id => $value)
			{
				if (!$value)
				{
					return false;
				}
			}
		}
		else
		{//propertyId非空时
			if (!$this->propertysMark[$propertyId])
			{
				return false;
			}
		}
		
		return true;
	}
}
