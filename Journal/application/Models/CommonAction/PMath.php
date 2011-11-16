<?php
class Models_CommonAction_PMath
{
	const RADIUS_EARTH	= 6371;
	
	/**
	 * 
	 * 计算两点（经度、纬度）之间的距离（单位：公里）
	 * @param double $latitudeA
	 * @param double $longitudeA
	 * @param double $latitudeB
	 * @param double $longitudeB
	 * @return double
	 */
	public static function distanceBtwLatLng($latitudeA , $longitudeA , $latitudeB , $longitudeB)
	{
		$R = self::RADIUS_EARTH;
		$dLat_rad = deg2rad($latitudeB- $latitudeA);
		$dLon_rad = deg2rad($longitudeB- $longitudeA);
		$latitudeA_rad = deg2rad($latitudeA);
		$latitudeB_rad = deg2rad($latitudeB);	
		
		$sin_dLat_half = sin($dLat_rad/2);
		$sin_dLon_half = sin($dLon_rad/2);
		$cos_lat1 = cos($latitudeA_rad);
		$cos_lat2 = cos($latitudeB_rad);
		
		$a = $sin_dLat_half*$sin_dLat_half + 
			$cos_lat1 * $cos_lat2 *
			$sin_dLon_half * $sin_dLon_half;
		
		$a_sqrt = sqrt($a);
		$a1_sqrt = sqrt(1-$a);
		
		$c = 2 * atan2($a_sqrt, $a1_sqrt);
		
		$dis = $R * $c;
		
		return $dis;
	}
	
	/**
	 * 
	 * 返回一个点为中心的正方形范围
	 * @param float $latitude
	 * @param float $longitude
	 * @param float	$distance
	 * @return	array(float $leftLon, float $rightLon, float $topLat, float $bottomLat)
	 * 			$leftLon	左边框的经度
	 * 			$rightLon	右边框的经度
	 * 			$topLat		上边框的纬度
	 * 			$bottomLat	下边框的纬度
	 */
	public static function  boundOfLatLng($latitude , $longitude , $distance)
	{
		if ($latitude == null || $longitude == null || $distance == null)
			return null;
			
		//未经测试
		$leftLon = null;
		$topLat = null;
		$rightLon = null;
		$bottomLat = null;
		
		$leftLatLng = self::getLatLng($latitude, $longitude, 270, $distance);
		$topLatLng = self::getLatLng($latitude, $longitude, 0, $distance);
		$rightLatLng = self::getLatLng($latitude, $longitude, 90, $distance);
		$bottomLatLng = self::getLatLng($latitude, $longitude, 180, $distance);
		
		$leftLon  = $leftLatLng[0];
		$rightLon = $rightLatLng[0];
		$topLat = $topLatLng[1];
		$bottomLat = $bottomLatLng[1];

		return array($leftLon , $rightLon , $topLat , $bottomLat);
	}

	/**
	 * 
	 * 从某点出发，指向一个方向，得到一段距离外的某点的经纬度
	 * @param float $latitudeA		起始点（纬度）
	 * @param float $longitudeA		起始点（经度）
	 * @param float $bearing		方向（角度）
	 * @param float $distance		距离
	 * @return	array(float $longitude , float $latitude)
	 */
	public static function getLatLng($latitudeA, $longitudeA, $bearing, $distance)
	{
		//未经测试
		$R = self::RADIUS_EARTH;
	
		$latitudeA = deg2rad($latitudeA);
		$longitudeA = deg2rad($longitudeA);
		$bearing = deg2rad($bearing);
		
		$d_divide_R = $distance / $R;
		$sin_latitudeA = sin($latitudeA);
		$cos_d_divide_R = cos($d_divide_R);
		$cos_latitudeA = cos($latitudeA);
		$sin_d_divide_R = sin($d_divide_R);
		$cos_bearing = cos($bearing);
		
		$latitudeB = asin(
					$sin_latitudeA * $cos_d_divide_R + 
					$cos_latitudeA * $sin_d_divide_R * $cos_bearing
				);
		
		$sin_bearing = sin($bearing);
		$sin_latitudeB = sin($latitudeB);
		
		$longitudeB = $longitudeA + atan2(
					$sin_bearing * $sin_d_divide_R * $cos_latitudeA , 
					$cos_d_divide_R - $sin_latitudeA * $sin_latitudeB
				);
		
		$pi = pi();
		$pi_2 = $pi/2;
		$pi2 = 2*$pi;
		$pi3_2 = 3*$pi_2;
		/**
		 * 区间【-$pi/2 , 0）		-$pi/2 <= $latitude < 0			--->	$latitudeB			
		 * 区间【-3*$pi/2 , -$pi/2）	-3*$pi/2 <= $latitude < -$pi/2	--->	-$pi-$latitudeB
		 * 区间（-2*$Pi , -3*$pi/2）	-2*$pi < $latitude < -3*$pi/2	--->	2*$pi + $latitudeB
		 * 
		 * 区间【0 , $Pi/2】			0 <= $latitudeB <= $pi/2		--->	$latitudeB
		 * 区间（$Pi/2 , 3*$pi/2】	$pi/2< $latitudeB <= 3*$pi/2 	--->	$pi-$latitudeB
		 * 区间（3*$pi/2 , 2*$pi）	3*$pi/2 < $latitudeB			--->	$latitudeB-2*$pi
		 */
		if ($latitudeB > $pi_2 || $latitudeB < (-$pi_2))
		{
			$latitudeB %= $pi2;
			if ($latitudeB > 0)
			{
				if ($latitudeB <= $pi3_2)
				{
					$latitudeB = $pi - $latitudeB;
				}
				else
				{
					$latitudeB = $latitudeB - $pi2;
				}
			}
			else
			{
				if ($latitudeB >= (-$pi3_2))
				{
					$latitudeB = -$pi - $latitudeB;
				}
				else
				{
					$latitudeB = $pi2 + $latitudeB;
				}
			}
		}
		/**
		 * 区间【-$pi , 0）		-$pi <= $longitudeB < 0			--->	$longitueB
		 * 区间（-2*$pi ,-$pi）	-2*$pi < $longitudeB < -$Pi		--->	$longitudeB+2*$Pi
		 * 
		 * 区间【0 , $pi】		0 <= $longitudeB <= $pi			--->	$longitudeB
		 * 区间（$Pi , 2*$pi）	$pi < $longitudeB < 2*$pi		--->	$longitudeB-2*$pi		
		 */
		if ($longitudeB > $pi || $longitudeB < (-$pi))
		{
			$longitudeB %= $pi2;
			
			if ($longitudeB > 0)
			{
				$longitudeB = $longitudeB - $pi2;
			}
			else
			{
				$longitudeB = $longitudeB + $pi2;
			}
		}
				
		$latitudeB = rad2deg($latitudeB);
		$longitudeB = rad2deg($longitudeB);
		
		return  array( $longitudeB , $latitudeB);	
	}

	/**
	 * 
	 * 检测某点是否在圆内
	 * @param float $latitudeCenter
	 * @param float $longitueCenter
	 * @param float $radius
	 * @param float $latitudeTest
	 * @param float $longitudeTest
	 * @return	boolean			点在圆内，返回true；否则，返回false
	 */
	public static function pointInSpehre($latitudeCenter , $longitudeCenter ,$radius , $latitudeTest , $longitudeTest)
	{
		$distance = self::distanceBtwLatLng($latitudeCenter, $longitudeCenter, $latitudeTest, $longitudeTest);	
		
		if ($distance <= $radius)
		{
			return true;
		}
		else
		{
			return false;
		}
	}	

}


////测纬度 [0,pi/2]
//$latitudeA = 1;
//$longitudeA= 0;
//$bearing = 0;
//$distance = 6000;
//$pos = Models_CommonAction_PMath::getLatLng($latitudeA, $longitudeA, $bearing, $distance);
//print_r($pos);
////测纬度 (pi/2 , pi]
//$latitudeA = 1;
//$longitudeA= 0;
//$bearing = 0;
//$distance = 15000;
//$pos = Models_CommonAction_PMath::getLatLng($latitudeA, $longitudeA, $bearing, $distance);
//print_r($pos);
////测纬度 (pi , 3*pi/2]
//$latitudeA = 1;
//$longitudeA= 0;
//$bearing = 0;
//$distance = 30000;
//$pos = Models_CommonAction_PMath::getLatLng($latitudeA, $longitudeA, $bearing, $distance);
//print_r($pos);
////测纬度(3*pi/2 , 2*pi)
//$latitudeA = 1;
//$longitudeA= 0;
//$bearing = 0;
//$distance = 45000;
//$pos = Models_CommonAction_PMath::getLatLng($latitudeA, $longitudeA, $bearing, $distance);
//print_r($pos);

////测纬度[-pi/2 , 0)
//$latitudeA = -1;
//$longitudeA= 0;
//$bearing = 180;
//$distance = 6000;
//$pos = Models_CommonAction_PMath::getLatLng($latitudeA, $longitudeA, $bearing, $distance);
//print_r($pos);
////测纬度[-pi , -pi/2)
//$latitudeA = -1;
//$longitudeA= 0;
//$bearing = 180;
//$distance = 15000;
//$pos = Models_CommonAction_PMath::getLatLng($latitudeA, $longitudeA, $bearing, $distance);
//print_r($pos);
////测纬度[-3*pi/2 , -pi)
//$latitudeA = -1;
//$longitudeA= 0;
//$bearing = 180;
//$distance = 30000;
//$pos = Models_CommonAction_PMath::getLatLng($latitudeA, $longitudeA, $bearing, $distance);
//print_r($pos);
////测纬度(-2*pi , -3*pi/2)
//$latitudeA = -1;
//$longitudeA= 0;
//$bearing = 180;
//$distance = 45000;
//$pos = Models_CommonAction_PMath::getLatLng($latitudeA, $longitudeA, $bearing, $distance);
//print_r($pos);
//
////测经度[0 , pi]
//$latitudeA = 20;
//$longitudeA= 0;
//$bearing = 90;
//$distance = 10000;
//$pos = Models_CommonAction_PMath::getLatLng($latitudeA, $longitudeA, $bearing, $distance);
//print_r($pos);
////测经度(pi , 2*pi)
//$latitudeA = 20;
//$longitudeA= 0;
//$bearing = 90;
//$distance = 20000;
//$pos = Models_CommonAction_PMath::getLatLng($latitudeA, $longitudeA, $bearing, $distance);
//print_r($pos);
////测经度[-pi , 0)
//$latitudeA = 20;
//$longitudeA= 0;
//$bearing = 270;
//$distance = 10000;
//$pos = Models_CommonAction_PMath::getLatLng($latitudeA, $longitudeA, $bearing, $distance);
//print_r($pos);
////测经度(-2*pi , -pi)
//$latitudeA = 20;
//$longitudeA= 0;
//$bearing = 270;
//$distance = 20000;
//$pos = Models_CommonAction_PMath::getLatLng($latitudeA, $longitudeA, $bearing, $distance);
//print_r($pos);
