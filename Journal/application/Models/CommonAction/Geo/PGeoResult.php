<?php
class Models_CommonAction_Geo_PGeoResult
{
	private $placeArr = array();
	private $placeCount = 0;
	public $hasMore = false;

	public function __construct()
	{
		
	}
	
	/**
	 * 
	 * 清空结果集
	 */
	public function clearResult()
	{
		$this->placeArr = array();
		$this->placeCount = 0;
	}
	
	/**
	 * 
	 * 将两个结果集合并
	 * @param Models_CommonAction_Geo_PGeoResult $result
	 */
	public function merge($result)
	{
		$newPlaceArr = $result->getPlaceArr();
		
		$this->placeArr = array_merge($this->placeArr , $newPlaceArr);
		
		$this->placeCount += count($newPlaceArr);
	}
	
	/**
	 * 
	 * 添加一个地方到结果集
	 * @param Models_CommonAction_Geo_PGeoPlace $place
	 */
	public function addPlace($place)
	{
		array_push($this->placeArr, $place);		
		$this->placeCount++;
	}
	
	/**
	 * 
	 * 返回一个结果集中的地方（Models_CommonAction_Geo_PGeoPlace对象）
	 * @param int $index
	 */
	public function getPlace($index)
	{
		$placeObj = null;
		
		if ($index < $this->placeCount)
		{	
			$placeObj = $this->placeArr[$index];
		}
		
		return $placeObj;
	}
	
	/**
	 * @return the $placeArr
	 */
	public function getPlaceArr() {
		return $this->placeArr;
	}

	/**
	 * @return the $placeCount
	 */
	public function getPlaceCount() {
		return $this->placeCount;
	}
	/**
	 * @return the $hasMore
	 */
	public function getHasMore() {
		return $this->hasMore;
	}

	/**
	 * @param boolean $hasMore
	 */
	public function setHasMore($hasMore) {
		$this->hasMore = $hasMore;
	}

}

