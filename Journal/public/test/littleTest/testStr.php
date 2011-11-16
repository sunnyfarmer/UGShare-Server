<?php
date_default_timezone_set('Asia/Shanghai');
//$curPath = dirname(__FILE__);
//$bootDir = realpath($curPath . '/../../../application/PCommonBoot.php');
//require_once $bootDir;
//PCommonBoot::init(); //初始化设置


//
// $date='08/26/2003';
//
// print ereg_replace("([0-9]+)/([0-9]+)/([0-9]+)",'\\1/\\1/\\3',$date);
//
//$a = 31;
//$a &= $a-1;
//
//echo $a;

//echo date('r');

//$value = json_encode(array('a', 'b', 'c'));
//$filename = './a.txt';
////read all
//$content = file_get_contents($filename);
//if ($content) {
//	echo "$content\n";
//} else {
//	echo "no content\n";
//}
//
////erase and write a word
////$fo = fopen($filename, 'wt');
////fwrite($fo, $value);
//
////add a word
//$fo = fopen($filename, 'at');
//fwrite($fo, $value);



//$arr1 = array(0, 1);
//$arr2 = array(1, 2);
//$arr = array_merge($arr2, $arr1);
//print_r($arr);

$year = 1900;
$month = 1;

$vArr = array();
for($year = 1900 ; $year < 3000 ; $year++) {
	for($month = 1 ; $month <= 12 ; $month++) {
		$value = $year * $month;
		if (!in_array($value, $vArr)) {
			array_push($vArr, $value);
		}else {
			echo "shit:";
		}
		echo "$year * $month = $value   ";
	}
}
echo 'finish';


























