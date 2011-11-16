<?php
//require_once dirname(__FILE__).'./PBehaviorEnum.php';
class Models_Behavior_PBehaviorNet
{	
	/**
	 * 
	 * 返回behaviorLine
	 * @param string $behaviorLineId
	 */
	public static function getBehaviorLine($behaviorLineId ,$net = null)
	{
		$line = null;
		if (!$net)
		{
			$net = simplexml_load_file(BEHAVIORNETXMLPATH);		
		}
		foreach ($net as $line)
		{
			if ($behaviorLineId == strval($line['id']))
			{
				$lineObj = new Models_Behavior_PBehaviorLine();				//新建一个PBehaviorLine对象
				$lineObj->setId($behaviorLineId);				//设置PBehaviorLine对象的id
				$lineObj->setDescription(strval($line['Description']));	//设置PbehaviorLine对象的Description
								
				//从XML中获取beginNode的对象
				$xmlBeginNode = $line->BeginNode[0];
				$lineObj->setBeginNode(strval($xmlBeginNode['id']));		//设置PBehaviorLine对象的beginNode的id
				//获取所有parameterValue
				$beginParameters = null;
				$xmlBeginParameterIndex = 0;
				$xmlBeginParameter = $xmlBeginNode->ParameterValue[$xmlBeginParameterIndex];
				while ($xmlBeginParameter) {
					$beginParameters[strval($xmlBeginParameter['id'])] = array(
						strval($xmlBeginParameter['comparisionID']),
						strval($xmlBeginParameter['comparisionObject'])
					);
					
					$xmlBeginParameterIndex++;
					$xmlBeginParameter = $xmlBeginNode->ParameterValue[$xmlBeginParameterIndex];
				}
				$lineObj->setBeginParameters($beginParameters);		//设置PBehaviorLine对象的beginParameters
				
				//获取所有conditions
				$conditions = null;
				$xmlConditionIndex = 0;
				$xmlCondition = $xmlBeginNode->Condition[$xmlConditionIndex];
				while ($xmlCondition) {
					$conditions[$xmlConditionIndex] = strval($xmlCondition['conditionId']);
					
					$xmlConditionIndex++;
					$xmlCondition = $xmlBeginNode->Condition[$xmlConditionIndex];		
				}
				$lineObj->setConditions($conditions);					//设置PBehaviorLine的Conditions
				
				//从XML中获取endNode的对象
				$xmlEndNode = $line->EndNode[0];
				$lineObj->setEndNode(strval($xmlEndNode['id']));		//设置PBehaviorLine的endNode的id
				//获取所有ParameterValue
				$endParameters = null;
				$xmlEndParameterIndex = 0;
				$xmlEndParameter = $xmlEndNode->ParameterValue[$xmlEndParameterIndex];							
				while ($xmlEndParameter) {
					$endParameters[strval($xmlEndParameter['id'])] = strval($xmlEndParameter['value']);	
										
					$xmlEndParameterIndex++;
					$xmlEndParameter = $xmlEndNode->ParameterValue[$xmlEndParameterIndex];							
				}
				$lineObj->setEndParameters($endParameters);			//设置PBehaviorLine对象的EndParameters
								
				return $lineObj;
			}
		}
	}
	

	/**
	 * 
	 * 返回所有已behavior为beginNode的behaviorLine
	 * @param string $behaviorId
	 */
	public static function getBehaviorLinesByBeginNodeId($behaviorId) 
	{
		$lineArray = array();
		
		$line = null;
		$net = simplexml_load_file(BEHAVIORNETXMLPATH);
		
		foreach ($net as $line)
		{
			$beginNode = $line->BeginNode[0];
			if ( $behaviorId == strval($beginNode['id']))
			{
				$behaviorLineId = strval($line['id']);
				array_push($lineArray, self::getBehaviorLine($behaviorLineId,$net));
			}
		}
		
		return $lineArray;
	}
	
	/**
	 * 
	 * 返回所有已behavior为endNode的behaviorLine
	 * @param string $behaviorId
	 */
	public static function getBehaviorLinesByEndNodeId($behaviorId)
	{
		$lineArray = array();
		
		$line = null;
		$net = simplexml_load_file(BEHAVIORNETXMLPATH);
		
		foreach ($net as $line)
		{
			$endNode = $line->EndNode[0];
			if ( $behaviorId == strval($endNode['id']))
			{
				$behaviorLineId = strval($line['id']);
				array_push($lineArray, self::getBehaviorLine($behaviorLineId,$net));
			}
		}
		
		return $lineArray;
	}
	
	/**
	 * 
	 * 添加一个PBehaviorLine对象
	 * @param Models_Behavior_PBehaviorLine $behaviorLine
	 */
	public static function addBehaviorLineToXML($behaviorLine)
	{
		//如果PBehaviorLine对象不符合条件，那么直接返回false
		if (!$behaviorLine->isPass())
			return false;
		
		$path = BEHAVIORNETXMLPATH;
		$doc = new DOMDocument();
		$doc -> formatOutput = true;
		
		if(!$doc -> load($path)) 
		{
			return false;
		}
		$root = $doc->documentElement;	//获取根节点BehaviorNet
		//behaviorLine属性
			//创建child（BehaviorLine），并创建attribute
		$line = $doc->createElement('BehaviorLine');
		$lineId = $doc->createAttribute('id');
		$lineDescription = $doc->createAttribute('Description');
			//创建id、description的textnode
		$lineId_value_node = $doc->createTextNode($behaviorLine->getId());
		$lineDescription_value_node = $doc->createTextNode($behaviorLine->getDescription());
			//将textnode赋予attribute
		$lineId->appendChild($lineId_value_node);
		$lineDescription->appendChild($lineDescription_value_node);	
			//将attribute赋予child（BehaviorLine）	
		$line->appendChild($lineId);
		$line->appendChild($lineDescription);
		
		//设置beginNode
			//创建child（BeginNode），并创建其id
		$xmlBeginNode = $doc->createElement('BeginNode');
		$xmlBeginNode_id = $doc->createAttribute('id');
			//创建id的textnode
		$xmlBeginNode_id_node = $doc->createTextNode($behaviorLine->getBeginNode());
			//将textnode赋予attribute
		$xmlBeginNode_id->appendChild($xmlBeginNode_id_node);
			//将attribte赋予child（BeginNode）
		$xmlBeginNode->appendChild($xmlBeginNode_id);
		
			//创建beginNode的child（ParameterValue）
		$beginParameters = $behaviorLine->getBeginParameters();		
		foreach ($beginParameters as $parameterId=>$comparision)
		{
				//创建child，并创建attribute
			$xmlParameterValue = $doc->createElement('ParameterValue');	
			$xmlParameterValue_id = $doc->createAttribute('id');
			$xmlParameterValue_comparisionId = $doc->createAttribute('comparisionID');
			$xmlParameterValue_comparisionObject = $doc->createAttribute('comparisionObject');	
				//创建textnode
			$xmlParameterValue_id_textnode = $doc->createTextNode($parameterId);
			$xmlParameterValue_comparisionId_textnode = $doc->createTextNode($comparision[0]);
			$xmlParameterValue_comparisionObject_textnode = $doc->createTextNode($comparision[1]);
				//将textnode赋予attribute
			$xmlParameterValue_id->appendChild($xmlParameterValue_id_textnode);
			$xmlParameterValue_comparisionId->appendChild($xmlParameterValue_comparisionId_textnode);
			$xmlParameterValue_comparisionObject->appendChild($xmlParameterValue_comparisionObject_textnode);
				//将attribute赋予child
			$xmlParameterValue->appendChild($xmlParameterValue_id);
			$xmlParameterValue->appendChild($xmlParameterValue_comparisionId);
			$xmlParameterValue->appendChild($xmlParameterValue_comparisionObject);
				//将child插入上各节点BeginNode中
			$xmlBeginNode->appendChild($xmlParameterValue);	
		}
		
		//创建beginNode的chlid（PropertyValue）
		$conditions = $behaviorLine->getConditions();
		foreach ($conditions as $conditionId)
		{
			//创建child，并创建attribute
			$xmlCondition = $doc->createElement('Condition');	
			$xmlCondition_conditionId = $doc->createAttribute('ConditionId');
			//创建textnode
			$xmlPropertyValue_value_textnode = $doc->createTextNode($conditionId);
				//将textnode赋予attribute
			$xmlCondition_conditionId->appendChild($xmlPropertyValue_value_textnode);
				//将attribute赋予child
			$xmlCondition->appendChild($xmlCondition_conditionId);
				//将child插入上各节点BeginNode中
			$xmlBeginNode->appendChild($xmlCondition);
		}
			//将child（BeginNode）赋予child（BehaviorLine）
		$line->appendChild($xmlBeginNode);
		
		//设置endNode
			//创建child（EndNode），并创建id
		$xmlEndNode = $doc->createElement('EndNode');
		$xmlEndNode_id = $doc->createAttribute('id');
			//创建textnode
		$xmlEndNode_id_textnode = $doc->createTextNode($behaviorLine->getEndNode());
			//将textnode赋予attribute
		$xmlEndNode_id->appendChild($xmlEndNode_id_textnode);
			//将attribute赋予child（EndNode）
		$xmlEndNode->appendChild($xmlEndNode_id);
			//处理EndNode的parameterValue
		$endParameters = $behaviorLine->getEndParameters();	
		foreach ($endParameters as $id=>$value)
		{
				//创建child（ParameterValue），并创建id、value
			$xmlEndParameterValue = $doc->createElement('ParameterValue');
			$xmlEndParameterValue_id = $doc->createAttribute('id');
			$xmlEndParameterValue_value = $doc->createAttribute('value');
				//创建attribute的textnode
			$xmlEndParameterValue_id_textnode = $doc->createTextNode($id);
			$xmlEndparametervalue_value_textnode = $doc->createTextNode($value);
				//将textnode赋予attribute
			$xmlEndParameterValue_id->appendChild($xmlEndParameterValue_id_textnode);
			$xmlEndParameterValue_value->appendChild($xmlEndparametervalue_value_textnode);
				//将attribute赋予child（ParameterValue）
			$xmlEndParameterValue->appendChild($xmlEndParameterValue_id);
			$xmlEndParameterValue->appendChild($xmlEndParameterValue_value);	
				//将child（Parametervalue）插入到child（EndNode）中
			$xmlEndNode->appendChild($xmlEndParameterValue);
		}
			//将child（EndNode）插入到child（BehaviorLine）中
		$line->appendChild($xmlEndNode);
		//child（BehaviorLine）插入到root子节点中
		$root->appendChild($line);
		
		//大功告成，保存xml
		$doc -> save($path);
		return true;
	}
	
	/**
	 * 
	 * 删除一个PBehaviorLine对象
	 * @param string $behaviorLineId
	 */
	public static function deleteBehaviorLineFromXML($behaviorLineId)
	{		
		$path = BEHAVIORNETXMLPATH;
		$doc = new DOMDocument();
		$doc -> formatOutput = true;
		
		if(!$doc -> load($path)) 
		{
			return false;
		}
		$net = $doc->documentElement;
		$behaviorArray = $net->getElementsByTagName('BehaviorLine');
		foreach ($behaviorArray as $behavior)
		{
			if($behaviorLineId ==  strval($behavior->getAttribute('id')))
			{
				$net->removeChild($behavior);
			}	
		}
	}
}