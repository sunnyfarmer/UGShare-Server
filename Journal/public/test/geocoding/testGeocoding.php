<?php
date_default_timezone_set('Asia/Shanghai');
$curPath = dirname(__FILE__);
$bootDir = realpath($curPath . '/../../../application/PCommonBoot.php');
require_once $bootDir;
PCommonBoot::init(); //初始化设置


$geo = new Models_CommonAction_PGeocoding('艾瑟顿' , '40','40');

echo $geo->getCountry_long().'<br>';
echo $geo->getCountry_short().'<br>';
echo $geo->getAreaLevel1_long().'<br>';
echo $geo->getAreaLevel1_short().'<br>';
echo $geo->getAreaLevel2_long().'<br>';
echo $geo->getAreaLevel2_short().'<br>';
echo $geo->getAreaLevel3_long().'<br>';
echo $geo->getAreaLevel3_short().'<br>';
echo $geo->getLocality_long().'<br>';
echo $geo->getLocality_short().'<br>';
echo $geo->getSubLocality_long().'<br>';
echo $geo->getSubLocality_short().'<br>';
echo $geo->getRoute_long().'<br>';
echo $geo->getRoute_short().'<br>';
echo $geo->getStreet_address_long().'<br>';
echo $geo->getStreet_address_short().'<br>';
echo $geo->getStreet_number_long().'<br>';
echo $geo->getStreet_number_short().'<br>';
echo $geo->getPlaceName_long().'<br>';
echo $geo->getPlaceName_short().'<br>';
echo $geo->getType().'<br>';
echo $geo->getFormattedAddress().'<br>';
