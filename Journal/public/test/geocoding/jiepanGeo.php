<?php
date_default_timezone_set('Asia/Shanghai');
$curPath = dirname(__FILE__);
$bootDir = realpath($curPath . '/../../../application/PCommonBoot.php');
require_once $bootDir;
PCommonBoot::init(); //初始化设置


$geo = new Models_CommonAction_Geo_PGeoJiepan();

$bool = $geo->searchNearBy("佰金", 39.1, 116.1, 10, 1, 10);//searchInArea("佰金", "北京", 1, 10);//(39.1, 116.1, 1, 10);
$rs = $geo->getResult();

print_r($rs);

	
//$rs = json_decode(
//	'{"has_more": true, "items": [{"has_surprise": false, "name": "\u767d\u6d0b\u6dc0\u6587\u5316\u82d1", "lon": 116.051554748193, "lat": 39.109852633688398, "guid": "D7272243D8F1E32A", "categories": [], "addr": "\u5b89\u65b0\u53bf\u767d\u6d0b\u6dc0\u666f\u533a\u5185\u00a0\u00a0"}, {"has_surprise": false, "name": "\u96c4\u53bf\u670d\u52a1\u533a", "lon": 116.174331512611, "lat": 39.085752501855801, "guid": "C462B8A65DD3FD74C918B55803F8626E", "categories": [], "addr": "\u6cb3\u5317\u7701\u4fdd\u5b9a\u5e02\u96c4\u53bfS7 Jinbao Expy"}, {"has_surprise": false, "name": "\u767d\u6c9f\u56fd\u9645\u5c0f\u5546\u54c1\u57ce", "lon": 116.039659148193, "lat": 39.106477278141803, "guid": "A44697530A5A14E9DA88F011BD8B6C43", "categories": [], "addr": "\u6cb3\u5317\u7701\u4fdd\u5b9a\u5e02\u9ad8\u7891\u5e97\u5e02\u5bcc\u6c11\u8def"}, {"has_surprise": false, "name": "\u5927\u6d6a\u6dd8\u6c99", "lon": 116.050998148193, "lat": 39.129827970547304, "guid": "A00ADE461C08C043E2A21C6AC0BB3CF5", "categories": [], "addr": "\u6cb3\u5317\u7701\u4fdd\u5b9a\u5e02\u9ad8\u7891\u5e97\u5e02\u4eac\u767d\u8def"}, {"has_surprise": false, "name": "\u767d\u6c9f\u53cb\u8c0a\u8def\u6d3e\u51fa\u6240", "lon": 116.03694186334, "lat": 39.116900314474101, "guid": "46AA7AD9F3F90482EFDFCEE5DF953383", "categories": [], "addr": "\u53cb\u8c0a\u4e1c\u8def"}, {"has_surprise": false, "name": "\u653f\u5e9c\u5927\u697c", "lon": 116.111496996078, "lat": 38.9974584630992, "guid": "0DAC699DD92CCF9A9D8BCE99A66F0F4F", "categories": [], "addr": "\u6cb3\u5317\u7701\u4fdd\u5b9a\u5e02\u96c4\u53bf\u96c4\u5dde\u8def"}, {"has_surprise": false, "name": "\u767d\u6d0b\u6dc0", "lon": 115.97117860547, "lat": 38.942154744504002, "guid": "1CEEE28BE8A1657552592C07F9720D0C", "categories": [], "addr": "\u6cb3\u5317\u7701\u4fdd\u5b9a\u5e02\u5b89\u65b0\u53bf\u767d\u6d0b\u6dc0\u666f\u533a"}, {"has_surprise": false, "name": "\u96c4\u53bf\u6e29\u6cc9\u6e56", "lon": 116.096369, "lat": 38.984712000000002, "guid": "4DDFFA3CAF0D52C2B3ADB7BCF4E129C9", "categories": [], "addr": "\u4fdd\u5b9a\u96c4\u53bf"}, {"has_surprise": false, "name": "\u96c4\u53bf\u519c\u8d38\u5e02\u573a", "lon": 116.096369, "lat": 38.984712000000002, "guid": "47A5F2CD23DCEEEA93CB3884A80FC5E4", "categories": [], "addr": "\u4fdd\u5b9a\u96c4\u53bf"}, {"has_surprise": false, "name": "\u767d\u6d0b\u6dc0\u666f\u533a", "lon": 115.93518539356199, "lat": 38.936561421556597, "guid": "0D1CB196B0E2189E7458DE024C2A89A6", "categories": [], "addr": "\u6cb3\u5317\u7701\u4fdd\u5b9a\u5e02\u5b89\u65b0\u53bf\u767d\u6d0b\u6dc0"}], "province": "\u5317\u4eac"}',
//	true
//);
//print_r($rs);
	
