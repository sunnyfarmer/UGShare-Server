<?php
abstract class Models_CommonAction_Geo_PGeographic
{
	/**
	 * 
	 * 地理信息结果集
	 * @var Models_CommonAction_Geo_PGeoResult
	 */	
	protected $result = null;
	
	protected $isResultSet = false;
	
	public function __construct()
	{
		$this->result = new Models_CommonAction_Geo_PGeoResult();
	}
	
	/**
	 * 
	 * 搜索地址（封装getCurAddress()）
	 * @param float $latitude
	 * @param float $longitude
	 * @param int $page
	 * @param int $rowCount
	 * @see Models_CommonAction_Geo_PGeographic::getCurAddress()
	 */
	public function getCurAddressEx($latitude , $longitude , $page , $rowCount)
	{
		if (!$this->canSearch())
		{
			return false;
		}
		
		return $this->getCurAddress($latitude, $longitude, $page, $rowCount);
	}
	
	/**
	 * 
	 * 搜索地址
	 * @param float $latitude
	 * @param float $longitude
	 * @param int $page
	 * @param int $rowCount
	 */
	protected abstract function getCurAddress($latitude , $longitude , $page , $rowCount);
	
	/**
	 * 
	 * 搜索级别区域内（国家、省、市）的地方 （封装searchInArea()）
	 * @param string $keyword
	 * @param string $area
	 * @param int $page
	 * @param int $rowCount
	 * @return	int		返回结果集的数量
	 * @see Models_CommonAction_Geo_PGeographic::searchInArea()
	 */
	public function searchInAreaEx($keyword , $area , $page , $rowCount)
	{
		if (!$this->canSearch())
		{
			return false;
		}
		return $this->searchInArea($keyword, $area, $page, $rowCount);
	}
	
	/**
	 * 
	 * 搜索级别区域内（国家、省、市）的地方
	 * @param string $keyword
	 * @param string $area
	 * @param int $page
	 * @param int $rowCount
	 * @return	int		返回结果集的数量
	 */
	protected abstract function searchInArea($keyword , $area , $page , $rowCount);
	
	/**
	 * 
	 * 搜索周边的地方（封装searchNearBy()）
	 * @param string $keyword
	 * @param float $latitude
	 * @param float $longitude
	 * @param float $radius
	 * @param int $page
	 * @param int $rowCount
	 * @return	int		返回结果集的数量
	 * @see Models_CommonAction_Geo_PGeographic::searchNearBy()
	 */
	public function searchNearByEx($keyword , $latitude , $longitude , $radius , $page , $rowCount)
	{
		if (!$this->canSearch())
		{
			return false;
		}
		
		return $this->searchNearBy($keyword, $latitude, $longitude, $radius, $page, $rowCount);
	}
	
	/**
	 * 
	 * 搜索周边的地方
	 * @param string $keyword
	 * @param float $latitude
	 * @param float $longitude
	 * @param float $radius
	 * @param int $page
	 * @param int $rowCount
	 * @return	int		返回结果集的数量
	 */
	protected abstract function searchNearBy($keyword , $latitude , $longitude , $radius , $page , $rowCount);
	
	/**
	 * 
	 * 清空上次搜索的结果集
	 */
	protected function clearResult()
	{
		$this->result->clearResult();
		$this->isResultSet = false;
	}
	
	/**
	 * 
	 * 能否进行搜索
	 * @return boolean	返回true，表示能够进行搜索；返回false，表示不能够进行搜索
	 */
	protected function canSearch()
	{
		//当结果集已经设置，返回false；结果集未设置，返回true
		return !($this->isResultSet);
	}
	
	protected function finishSearch()
	{
		$this->isResultSet = true;
	}
	
	/**
	 * @return the $result
	 */
	public function getResult() {
		return $this->result;
	}

}

