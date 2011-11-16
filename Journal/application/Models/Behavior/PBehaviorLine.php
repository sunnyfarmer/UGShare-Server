<?php
class Models_Behavior_PBehaviorLine
{
	private $id = null;
	private $description = null;
	
	private $beginNode = null;	//behaviorId
	private $beginParameters = null;	//行为参数的比较，形式如，array(parameterId=>array(comparisionId,comparisionObject))
	private $conditions = null;	//用户属性的比较,形式如array((PCondition的Id)，(PCondition的Id))

	private $endNode = null;	//behaviorId
	private $endParameters = null;	//触发行为的参数，形式如，array(id , value)

	
	/**
	 * 
	 * 检查对象的属性是否符合条件
	 */
	public function isPass()
	{
		if (null == $this->id || !is_string($this->id))
			return false;
		if (null == $this->description || !is_string($this->description))
			return false;
		if (null == $this->beginNode || !is_string($this->beginNode))
			return false;
		if (null == $this->beginParameters || !is_array($this->beginParameters))
			return false;
		if (null == $this->conditions || !is_array($this->conditions))
			return false;
		if (null == $this->endNode || !is_string($this->endNode))
			return false;
		if(null == $this->endParameters || !is_array($this->endParameters))
			return false;
		
		return true;
	}
	
	/**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return the $description
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @return the $beginNode
	 */
	public function getBeginNode() {
		return $this->beginNode;
	}

	/**
	 * @return the $beginParameters
	 */
	public function getBeginParameters() {
		return $this->beginParameters;
	}

	/**
	 * @return the $conditions
	 */
	public function getConditions() {
		return $this->conditions;
	}

	/**
	 * @return the $endNode
	 */
	public function getEndNode() {
		return $this->endNode;
	}

	/**
	 * @return the $endParameters
	 */
	public function getEndParameters() {
		return $this->endParameters;
	}

	/**
	 * @param field_type $id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @param field_type $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * @param field_type $beginNode
	 */
	public function setBeginNode($beginNode) {
		$this->beginNode = $beginNode;
	}

	/**
	 * @param field_type $beginParameters
	 */
	public function setBeginParameters($beginParameters) {
		$this->beginParameters = $beginParameters;
	}

	/**
	 * @param field_type $conditions
	 */
	public function setConditions($conditions) {
		$this->conditions = $conditions;
	}

	/**
	 * @param field_type $endNode
	 */
	public function setEndNode($endNode) {
		$this->endNode = $endNode;
	}

	/**
	 * @param field_type $endParameters
	 */
	public function setEndParameters($endParameters) {
		$this->endParameters = $endParameters;
	}
}