<?php
date_default_timezone_set('Asia/Shanghai');
$curPath = dirname(__FILE__);
$bootDir = realpath($curPath . '/../../../application/PCommonBoot.php');
require_once $bootDir;
PCommonBoot::init(); //初始化设置

$conn = Models_Core::getDoctrineConn();
$query = Doctrine_Query::create()
		->select('count(c.id)')
		->from('TrCity c')
		->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);

$sumRs = $query->fetchOne();
$sum = is_numeric($sumRs['count']) ? intval($sumRs['count']) : -1;

$conn = Models_Core::getDoctrineConn();
$query = Doctrine_Query::create()
		->select('c.id , c.longname')
		->from('TrCity c');
$cityArr = $query->execute();

function getNextCity()
{
	global $sum;
	global $cityArr;
	static $id = -1;
	if ($id < $sum)
	{
		$id++;
		return $cityArr[$id]->longname;
	}
	else 
	{
		return false;
	}
}
?>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=1.1&services=true"></script>
<script type="text/javascript" src="jquery-1.6.1.min.js"></script>
<script type="text/javascript">
var cityArr = new Array(
	<?php 
		$cityStr = '';
		while (true) 
		{
			$city = getNextCity();
			if ($city)
			{
				$cityStr .=  '"'.$city.'",';
			}
			else
			{
				break;
			}
			
		}
		$cityStr = rtrim($cityStr , ',');
		echo $cityStr;
	?>
);

var begin = 369;
var end = 400;
var cot = begin;

var options = {
	  onSearchComplete: function(results){
	    // 判断状态是否正确
	    if (local.getStatus() == BMAP_STATUS_SUCCESS){
			var s = [];

			var province = null;
			var city = null;

			province = results.province;	//得到当前搜索所在的省份
			city = results.city;			//得到当前搜索所在的城市

			for (var i = 0; i < results.getCurrentNumPois(); i ++)
			{
				//得到一个地点
				place = results.getPoi(i);

				lat = place.point.lat;
				lng = place.point.lng;
				placeName = place.title;
				address = place.address;
				
				//调用数据存储接口
				divResult = document.getElementById("divResult").innerHTML;
				$("#divResult").load("addDb.php", {"province" : province , "city" : city , "lat" : lat, "lng" : lng, "placeName" : placeName, "address" : address });

				document.getElementById("divResult").innerHTML += "  "+ divResult;

				s.push(lat+","+lng+","+placeName+","+address);
			}


	      	document.getElementById("results").innerHTML = document.getElementById("results").innerHTML+"<br/><br/>"+s.join("<br/>");
	    
			var curPageIndex = results.getPageIndex();	//当前的页数序号
			var numPages = results.getNumPages();		//总页数
			var nextPageIndex = curPageIndex+1;			//得到下一页的页数序号，在下面进行下一页的信息获取
			if( nextPageIndex < numPages)
			{
				local.gotoPage(nextPageIndex);
			}
			else
			{
				if(cot < cityArr.length && cot <= end)
				{
					local = new BMap.LocalSearch(
						cityArr[cot], 
						options
					); 
					local.search("景点");	

					cot++;
				}
				else
				{
					alert("finish"); 
				}
			}
		}
	  }
};


if(begin <cityArr.length)
{
	var local = new BMap.LocalSearch(cityArr[begin], options);
	local.search("景点");
}

</script>

<body>
<div id="results" style="font-size:13px;margin-top:10px;"></div>
<div id="divResult"></div>
</body>

