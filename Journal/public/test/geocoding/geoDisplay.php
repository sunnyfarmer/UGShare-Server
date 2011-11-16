<head>
<script type="text/javascript">
function onChange(type)
{
	proSelect = document.getElementById("ps");
	pro = proSelect.value;

	citySelect = document.getElementById("cs");
	city = citySelect.value;

	subSelect = document.getElementById("ss");
	sublocality = subSelect.value;

	isParaSet = false;
	url = "geoDisplay.php";

	if(type == 1)
	{
		url += "?pro=" + pro;
	}
	else if(type == 2)
	{
		url += "?city=" + city;
	}	
	else if(type== 3)
	{

	}
	
	self.location = url;
}

</script>
</head>

<?php 
date_default_timezone_set('Asia/Shanghai');
$curPath = dirname(__FILE__);
$bootDir = realpath($curPath . '/../../../application/PCommonBoot.php');
require_once $bootDir;
PCommonBoot::init(); //初始化设置

	$pro = isset($_GET['pro']) ? $_GET['pro'] : null;
	$city = isset($_GET['city']) ? $_GET['city'] : null;
	$sub = isset($_GET['sub']) ? $_GET['sub'] : null;

	$conn = Models_Core::getDoctrineConn();	
?>
<select id="ps" name="proSelect" onchange="onChange(1)">
<?php 
	$query = Doctrine_Query::create()
			->select('p.longname')
			->from('TrProvince p');
	$rs = $query->execute();
	if (count($rs))
	{
		foreach ($rs as $province)
		{
			$pName = $province->longname;
			echo "<option>$pName</option>";
		}
	}
?>
</select>

<select id="cs" name="citySelect" onchange="onChange(2)">
<?php 
	if ($pro)
	{
		$query = Doctrine_Query::create()
				->select('c.longname')
				->from('TrCity c')
				->leftJoin('c.TrProvince p')
				->where('p.longname = ?' , $pro);
		$rs = $query->execute();
		if (count($rs))
		{
			foreach ($rs as $city)
			{
				$cName = $city->longname;
				echo "<option>$cName</option>";
			}
		}
	}
?>
</select>

<select id="ss" name="subSelect" onchange="onChange(3)">
<?php 
	if ($city)
	{
		$query = Doctrine_Query::create()
				->select('s.longname')
				->from('TrSublocality s')
				->leftJoin('s.TrCity c')
				->where('c.longname = ?' , $city);
		$rs = $query->execute();
		if (count($rs))
		{
			foreach ($rs as $sublocality)
			{
				$sName = $sublocality->longname;
				echo "<option>$sName</option>";
			}
		}
	}
?>
</select>
