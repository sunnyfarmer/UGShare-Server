<?php


/**
 * @author samson
 * @version 1.0
 * @created 01-一月-2011 10:08:30
 */
class Models_Datatype_PDTPosition
{
	//地球半径（千米）
	const EARTHRADIUS = 6378.137;	

	private $latitude;
	private $longitude;

	function __construct($latitude , $longitude)
	{
		$this->latitude = $latitude;
		$this->longitude = $longitude;		
	}

	/**
	 * 在某点的东面
	 * 
	 * @param PPTPosition pos
	 */
	function easterThan($pos)
	{
		if ($this->longitude > $pos->getlongitude())
		{
			return true;
		}
		else
		{
			return false;	
		}
	}
	/**
	 * 在某点的西面
	 * 
	 * @param PPTPosition pos
	 */
	function westerThan($pos)
	{
		if ($this->longitude < $pos->getlongitude())
		{
			return true;
		}
		else
		{
			return false;	
		}
	}
	/**
	 * 在某点的北面
	 * 
	 * @param PPTPosition pos
	 */
	function northerThan($pos)
	{
		if ($this->latitude > $pos->getlatitude())
		{
			return true;
		}
		else
		{
			return false;	
		}
	}
		/**
	 * 在某点的南面
	 * 
	 * @param PPTPosition pos
	 */
	function southerThan($pos)
	{
		if ($this->latitude > $pos->getlatitude())
		{
			return true;
		}
		else
		{
			return false;	
		}
	}

	
	/**
	 * 获取两点之间的距离
	 * pos1( la1 , lo1) , pos2( la2 , lo2)
	 * a = la1 - la2;
	 * b = lo1 - lo2;
	 * sin2a2 = sin(a/2) * sin(a/2);
	 * sin2b2 = sin(b/2) * sin(b/2);
	 * cosla1 = cos(la1);
	 * cosla2 = cos(la2);
	 * arcsin = arcsin(sqrt(sin2a2+cosla1*cosla2*sin2b2));
	 * result = 2*arcsin*EARTHRADIUS;
	 * @param PPTPosition PPTPosition
	 */
	function getDistance($pos)
	{
		$a = $this->latitude - $pos->getlatitude();
		$b = $this->longitude - $pos->getlongitude();
		$sin2a2 = sin(a/2);
		$sin2a2*=$sin2a2;
		$sin2b2 = sin(b/2);
		$sin2b2*=$sin2b2;
		$cosla1 = cos($this->latitude);
		$cosla2 = cos($pos->getlatitude());
		$arcsin = asin(sqrt($sin2a2+$cosla1*$cosla2*$sin2b2));
		$result = 2*$arcsin*self::EARTHRADIUS;
		
		return $result;
	}

	/**
	 * 
	 * Enter description here ...
	 */
	function getlatitude()
	{
		return $this->latitude;
	}

	function getlongitude()
	{
		return $this->longitude;
	}
	/**
	 * 
	 * @param float newVal
	 */
	function setlatitude($newVal)
	{
		$this->latitude = $newVal;
	}

	/**
	 * 
	 * @param float newVal
	 */
	function setlongitude($newVal)
	{
		$this->longitude = $newVal;
	}


}
?>