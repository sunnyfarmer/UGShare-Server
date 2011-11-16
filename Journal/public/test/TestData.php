<?php
date_default_timezone_set('Asia/Shanghai');
$curPath = dirname(__FILE__);
$bootDir = realpath($curPath . '/../../application/PCommonBoot.php');
require_once $bootDir;
PCommonBoot::init(); //初始化设置


$conn = Models_Core::getDoctrineConn();

////user******************user******************user******************user******************
//$initPwd = '123';
//$iniTelephone = '123456';
//
//$conn->beginTransaction();
//
//$u1 = new TrUser();
//$u1->password = $initPwd;
//$u1->username = uniqid();
//$u1->telephone = $iniTelephone;
//$u1->TrEmail[0]->address = 'skyliang@21cn.com';
//$u1->save();
//
//$timestame = time();
//$cot = 0;
//while($cot++ < 100)
//{
//	$u2 = new TrUser();
//	$u2->password = $initPwd;
//	$u2->username = uniqid();
//	$u2->telephone = $timestame+$cot;
//	$u2->TrEmail[0]->address = uniqid();
//	
//	$u2->save();
//}
//$conn->commit();


//////user avatar
//$dir = 'D:\avatar';
//$avatarDir = dir($dir);
//$photoArr = array();
//while (null != ($file = $avatarDir->read()))
//{
//	$fileDir = "$dir\\$file";
//	if (is_file($fileDir))
//	{
//		array_push($photoArr, base64_encode(file_get_contents($fileDir)));
//	}
//}
//$photoCot = count($photoArr);
//
//$query = Doctrine_Query::create()
//		->select('u.id , u.telephone , u.password')	
//		->from('TrUser u');
//
//$users = $query->execute();
//foreach ($users as $user)
//{
//	$conn->beginTransaction();
//	
//	$telephone = $user->telephone;
//	$pwd = $user->password;
//	
//	Models_Api_User_PRpcLogin::login($telephone, $pwd);
//	
//	$avatar = $photoArr[rand(0 , $photoCot)];
//	
//	Models_Api_User_PRpcUserMethod::setAvatar($avatar);
//
//	$conn->commit();
//}


////////user relation***************user relation***************user relation***************user relation***************
//$query = Doctrine_Query::create()
//		->select('u.id')	
//		->from('TrUser u')
//		->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
//$usrArr = $query->execute();
//$usrCot = count($usrArr);
//
//$conn->beginTransaction();
//foreach ($usrArr as $usr)
//{
//	$cot = 0 ; 
//	$idArr = array();
//	while ($cot++ < 10)
//	{	
//		$usrId = $usr['id'];
//		array_push($idArr, $usrId);
//		
//		$otherUsrId = null;
//		do
//		{
//			$otherUsrId = $usrArr[rand(0, $usrCot-1)]['id'];
//		}while ( in_array($otherUsrId, $idArr));
//		
//		array_push($idArr, $otherUsrId);
//		
//		$usrRel = new TrUserToUser();
//		
//		$usrRel->usr_id_self_ref = $usrId;
//		$usrRel->usr_id_other_ref = $otherUsrId;
//		
//		$usrRel->save();
//	}
//}
//$conn->commit();
//
////country****************country****************country****************country****************
//$countrys = '中华人民共和国,韩国,蒙古,朝鲜,日本,菲律宾,越南,
//		老挝,柬埔寨,缅甸,泰国,马来西亚,文莱,新加坡,
//		印度尼西亚,东帝汶,尼泊尔,不丹,孟加拉国,印度,
//		巴基斯坦,斯里兰卡,马尔代夫,哈萨克斯坦,吉尔吉斯斯坦,
//		塔吉克斯坦,乌兹别克斯坦,土库曼斯坦,	阿富汗,伊拉克,
//		伊朗,叙利亚,约旦,黎巴嫩,以色列,巴勒斯坦,沙特阿拉伯,
//		巴林,卡塔尔,科威特,阿拉伯联合酋长国（阿联酋）,阿曼,
//		也门,格鲁吉亚,亚美尼亚,阿塞拜疆,土耳其,塞浦路斯,
//		芬兰,瑞典,挪威,冰岛,丹麦,爱沙尼亚,拉脱维亚,立陶宛,
//		白俄罗斯,俄罗斯,乌克兰,摩尔多瓦,波兰,捷克,斯洛伐克,
//		匈牙利,德国,奥地利,瑞士,列支敦士登,英国,爱尔兰,荷兰,
//		比利时,卢森堡,法国,罗马尼亚,保加利亚,塞尔维亚,黑山,
//		马其顿,阿尔巴尼亚,希腊,斯洛文尼亚,克罗地亚,波斯尼亚和黑塞哥维那,
//		意大利,梵蒂冈,圣马力诺,马耳他,西班牙,葡萄牙,安道尔,摩纳哥,
//		埃及,利比亚,苏丹，南苏丹,突尼斯,阿尔及利亚,摩洛哥,
//		埃塞俄比亚,厄立特里亚,索马里,吉布提,肯尼亚,坦桑尼亚,
//		乌干达,卢旺达,布隆迪,塞舌尔,乍得,中非,喀麦隆,赤道几内亚,
//		加蓬,刚果共和国[简称：刚果（布）],刚果民主共和国[简称：刚果（金）],
//		圣多美和普林西比,毛里塔尼亚,塞内加尔,冈比亚,马里,布基纳法索,
//		几内亚,几内亚比绍,佛得角,塞拉利昂,利比里亚,科特迪瓦,加纳,
//		多哥,贝宁,尼日尔,尼日利亚,赞比亚,安哥拉,津巴布韦,马拉维,
//		莫桑比克,博茨瓦纳,纳米比亚,南非,斯威士兰,莱索托,马达加斯加,
//		科摩罗,毛里求斯,澳大利亚,新西兰,巴布亚新几内亚,所罗门群岛,
//		瓦努阿图,密克罗尼西亚,马绍尔群岛,帕劳,瑙鲁,基里巴斯,图瓦卢,
//		萨摩亚,斐济群岛,汤加,库克群岛,纽埃,加拿大,美国,墨西哥,
//		危地马拉,伯利兹,萨尔瓦多,洪都拉斯,尼加拉瓜,哥斯达黎加,巴拿马,
//		巴哈马,古巴,牙买加,海地,多米尼加共和国,安提瓜和巴布达,
//		圣基茨和尼维斯,多米尼克,圣卢西亚,圣文森特和格林纳丁斯,格林纳达,
//		巴巴多斯,特立尼达和多巴哥,哥伦比亚,委内瑞拉,圭亚那,苏里南,
//		厄瓜多尔,秘鲁,玻利维亚,巴西,智利,阿根廷,乌拉圭,巴拉圭';
//
//$countrys = explode(',', $countrys);
//foreach ($countrys as $country)
//{	
//	$country = trim($country);
//	if ($country != '')
//	{
//		$coun = new TrCountry();
//		$coun->longname = $country;
//		$coun->shortname = $country;
//		$coun->save();
//	}
//}
//
//
////province*****************province*****************province*****************province*****************
//$provinces = '北京市 河北省 山西省 辽宁省 吉林省 黑龙江省江苏省 浙江省 安徽省 福建省 江西省 山东省 河南省 湖北省 湖南省 广东省 海南省 四川省 贵州省 云南省 陕西省 甘肃省 青海省 台湾省 内蒙古自治区 广西壮族自治区 宁夏回族自治区 新疆维吾尔自治区 西藏自治区 香港特别行政区 澳门特别行政区';
//
//$provinces = explode(' ', $provinces);
//
//$query = Doctrine_Query::create()
//		->from('TrCountry c')
//		->where('c.longname = ?' , '中华人民共和国');
//$china = $query->fetchOne();
//$cId = $china->id;
//$conn->beginTransaction();
//foreach ($provinces as $province)
//{
//	$provinceDb = new TrProvince();
//	$provinceDb->ctr_id_ref = $cId;
//	$provinceDb->longname = $province;
//	$provinceDb->shortname = $province;
//	$provinceDb->save();
//}
//
//$conn->commit();
//
////city***************city***************city***************city***************city***************
//$query = Doctrine_Query::create()
//		->from('TrProvince p')
//		->where('p.longname = ?' , '北京市');
//$province = $query->fetchOne();
//
//$pId = $province->id;
//
//$ctrDb = new TrCity();
//$ctrDb->pvc_id_ref = $pId;
//$ctrDb->longname = '北京';
//$ctrDb->shortname = '北京';
//
//$conn->flush();
//
////sublocality********************sublocality********************sublocality********************sublocality********************sublocality********************
//$sublocalitys = array('昌平区','顺义区','海淀区','怀柔区','门头沟区','石景山区','通州区','丰台区','房山区','大兴区');
//
//$query = Doctrine_Query::create()
//		->from('TrCity c')
//		->where('c.longname = ?' , '北京');
//$city = $query->fetchOne();
//$ctyId = $city->id;
//$conn->beginTransaction();
//foreach ($sublocalitys as $sub)
//{
//	$sublocalityDB = new TrSublocality();
//	$sublocalityDB->cty_id_ref = $ctyId;
//	$sublocalityDB->longname = $sub;
//	$sublocalityDB->shortname = $sub;
//	$sublocalityDB->save();
//}
//$conn->commit();
////
//////////place***************place***************place***************place***************place***************place***************
//$places = array(
//	'昌平区'=>array(
//		'定陵博物馆',
//		'昌平区博物馆',
//		'昭陵博物馆',
//		'十三陵明皇蜡像宫',
//		'九龙游乐园',
//		'沟崖自然风景区',
//		'天池风景区'
//	),
//	'顺义区'=>array(
//		'顺义公园',
//		'北京新国际展览中心',
//		'怡园公园',
//		'北京顺鑫绿色度假村',
//		'中国国际展览中心',
//		'焦庄户地道战遗址纪念馆',
//		'文物博物馆'
//	),
//	'海淀区'=>array(
//		'今日美术馆(文慧园北路店)',
//		'博雅塔',
//		'双榆树公园',
//		'香山公园',
//		'圆明园遗址公园',
//		'中国电信博物馆',
//		'应物会议中心',
//		'中华世纪坛',
//		'北京航空馆'
//	),
//	'怀柔区'=>array(
//		'北京湖景水上乐园',
//		'雁栖湖旅游区',
//		'百泉山风景区',
//		'雁栖湖',
//		'长城',
//		'智慧谷'
//	),
//	'门头沟区'=>array(
//		'黑山公园',
//		'潭柘戒台风景区',
//		'门头沟博物馆',
//		'门头沟区科技馆',
//		'珍珠湖风景区',
//		'灵山自然风景区',
//		'仙台旅游区',
//		'八奇洞',
//		'西藏博物园',
//		'双林寺'
//	),
//	'石景山区'=>array(		
//		'国际雕塑公园 ',
//		'石景山游乐园',
//		'石景山区科技馆',
//		'小土豆美食餐厅古城南里店',
//		'古城爱侬',
//		'八大处公园',
//		'国际雕塑公园',
//		'佳联古城北路店'
//	),
//	'通州区'=>array(
//		'万春园公园 ',
//		'中国民兵武器装备陈列馆',
//		'药王庙(通州区)',
//		'燃灯佛舍利塔',
//		'宋庄美术馆',
//		'生态公园',
//		'宋庄美术馆',
//		'大运河水梦园',
//		'通州博物馆 ',
//		'运河生态公园'
//	),
//	'丰台区'=>array(
//		'万芳亭公园',
//		'中国人民抗日战争纪念雕塑园',
//		'世界公园',
//		'佳乐凝春',
//		'草原春天',
//		'南苑教堂',
//		'东高地青少年科技馆',
//		'北京航天博物馆',
//		'第十二中学生态博物馆',
//		'世纪京华酒文化博物馆'
//	),
//	'房山区'=>array(
//		'北京房山云居寺',
//		'石花洞风景区',
//		'上方山国家森林公园 ',
//		'北京猿人遗址',
//		'燕山公园',
//		'仙栖洞',
//		'琉璃河商周遗址',
//		'灵鹫禅寺',
//		'白水寺'
//	),
//	'大兴区'=>array(
//		'中国西瓜博物馆',
//		'黄村儿童游乐园(教师进修学校北)',
//		'北普陀影视城',
//		'兴海公园',
//		'半壁店森林公园',
//		'太阳宫公园',
//		'街心公园',
//		'麋鹿苑博物馆',
//		'北京南海子麋鹿苑博物馆',
//		'宣颐公园'
//	),
//	'云南'=>array(
//		'金马碧鸡坊 ',   
//		'东寺塔',
//		'大观楼',
//		'云南省博物馆',
//		'七彩云南',
//		'七彩云南孔雀园',
//		'古镇',
//		'滇池海埂公园',
//		'滇池',
//		'金碧广场'
//	),
//	'西藏'=>array(
//		'大昭寺广场',
//		'哲蚌寺',
//		'罗布林卡',
//		'桑耶寺',
//		'布达拉宫',
//		'西藏博物馆',
//		'布达拉宫广场',
//		'和平解放纪念碑'
//	),
//	'新疆'=>array(
//		'新疆国际大巴扎',
//		'红山公园'.
//		'人民公园',
//		'人民广场',
//		'红山',
//		'乌鲁木齐西山老君庙',
//		'水上乐园',
//		'大佛寺',
//		'二道桥清真寺'   
//	)
//);
//////////without interface
////////$conn->beginTransaction();
////////foreach ($places as $sub=>$placeArr)
////////{
////////	$query = Doctrine_Query::create()
////////			->from('TrSublocality s')
////////			->where('s.longname = ?' , $sub);
////////	$sublocality = $query->fetchOne();		
////////	$subId = $sublocality->id;
////////	foreach($placeArr as $place)
////////	{
////////		$placeDb = new TrPlace();
////////		$placeDb->slc_id_ref = $subId;
////////		$placeDb->name = $place;
////////		$placeDb->save();
////////	}		
////////}
////////$conn->commit();
////////with interface
//Models_Api_User_PRpcLogin::login('123456', '123');
//$conn->beginTransaction();
//$cot = 0;
//foreach ($places as $sub=>$placeArr)
//{
//	foreach ($placeArr as $place)
//	{
//		$geo = new Models_CommonAction_PGeocoding($place);
//		Models_Api_Geographic_PRpcGeographicMethod::addPlace($geo->getPlaceName_long(), $geo->getLongitude(), $geo->getLatitude());		
//		
//		$cot++;
//		if ($cot >= 5)
//		{
//			$cot = 0;
//			$conn->commit();
//			$conn->beginTransaction();
//		}
//	}
//}
//$conn->commit();
////
//////place hot month
//$query = Doctrine_Query::create()
//		->select('p.id')
//		->from('TrPlace p');
//$placeArr = $query->execute();
//$conn->beginTransaction();
//foreach ($placeArr as $place)
//{
//	$pId = $place->id;
//
//	for($cot = 0 ; $cot < 3 ; $cot++)
//	{
//		try 
//		{
//			$placeMonth = new TrPlaceHotMonth();
//		
//			$placeMonth->month = rand(4, 7);
//			$placeMonth->plc_id_ref = $pId;
//			
//			$placeMonth->save();
//		}
//		catch (Exception $e)
//		{
//			$cot--;
//		}
//	}
//}
//$conn->commit();
//////////place tag
//Models_Api_User_PRpcLogin::login('123456', '123');
//$tags = array('沙滩','高山','森林','城市','越野','探险','纯美','浪漫');
//$query = Doctrine_Query::create()
//		->select('p.id')
//		->from('TrPlace p');
//$placeArr = $query->execute();
//$conn->beginTransaction();
//foreach ($placeArr as $place)
//{
//	$pId = $place->id;
//
//	for($cot = 0 ; $cot < 3 ; $cot++)
//	{
//		$tag = $tags[rand(0, 7)];
//		$result = Models_Api_Geographic_PRpcGeographicMethod::addPlaceTag($pId, $tag);
//		if ($result['STATUS'] == Models_Core::STATE_DB_ERROR)
//			$cot--;
//	}
//}
//$conn->commit();
//
//////agree tag
//Models_Api_User_PRpcLogin::login('123456', '123');
//$query = Doctrine_Query::create()
//		->select('pt.id')
//		->from('TrPlaceTag pt');
//$pTagArr = $query->execute();
//$conn->beginTransaction();
//foreach ($pTagArr as $pTag)
//{
//	$ptId = $pTag->id;
//	
//	$times = rand(0, 100);
//	
//	for ($cot = 0 ; $cot < $times ; $cot++)
//	{
//		$bAgree = rand(0, 1);
//		if ($bAgree)
//		{
//			Models_Api_Geographic_PRpcGeographicMethod::agreeTag($ptId);
//		}
//	}
//}
//$conn->commit();
		
////journal*************journal*************journal*************journal*************journal*************
//$beginIndex = 0;
//$rowCount = 30;
//do{
//	$query = Doctrine_Query::create()
//			->select('u.id , u.telephone , u.password')
//			->from('TrUser u')
//			->offset($beginIndex)
//			->limit($rowCount);
//	$userArr = $query->execute();
//	$conn->beginTransaction();
//	foreach ($userArr as $user)
//	{
//		$uId = $user->id;
//		$uTel = $user->telephone;
//		$uPwd = $user->password;
//		
//		Models_Api_User_PRpcLogin::login($uTel, $uPwd);
//		
//		Models_Api_Journal_PRpcJournalMethod::createJournal("北京游啊游**$uId**");
//		Models_Api_Journal_PRpcJournalMethod::createJournal("天津游啊游**$uId**");
//		Models_Api_Journal_PRpcJournalMethod::createJournal("河北游啊游**$uId**");
//		Models_Api_Journal_PRpcJournalMethod::createJournal("河南游啊游**$uId**");
//		Models_Api_Journal_PRpcJournalMethod::createJournal("河东游啊游**$uId**");
//	    Models_Api_Journal_PRpcJournalMethod::createJournal("广东游啊游**$uId**");
//	    Models_Api_Journal_PRpcJournalMethod::createJournal("广西游啊游**$uId**");
//	    Models_Api_Journal_PRpcJournalMethod::createJournal("湖南游啊游**$uId**");
//	    Models_Api_Journal_PRpcJournalMethod::createJournal("湖北游啊游**$uId**");
//	}
//	$conn->commit();
//
//	$beginIndex += $rowCount;
//}while (count($userArr)== $rowCount);

////comment journal
//Models_Api_User_PRpcLogin::login('123456', '123');
//
//$query = Doctrine_Query::create()
//		->select('j.id')
//		->from('TrJournal j');
//$jArr = $query->execute();
//
//$conn->beginTransaction();
//foreach($jArr as $j)
//{
//	$jId = $j->id;
//	for ($cot = 0 ; $cot < 10 ; $cot++)
//	{
//		Models_Api_Journal_PRpcJournalMethod::commentJournal($jId, $jId.'-'.$cot.'-'.'评论comment');
//	}
//}
//$conn->commit();

////journalPlace*******************journalPlace*******************journalPlace*******************journalPlace*******************
//$beginIndex = 0;
//$rowCount = 30;	
//$query = Doctrine_Query::create()
//	->select('p.id , p.name')
//	->from('TrPlace p')
//	->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
//$places = $query->execute();
//$placeCot = count($places);
//do{
//	$query = Doctrine_Query::create()
//			->select('j.id , u.id , u.telephone , u.password')
//			->from('TrJournal j')
//			->leftJoin('j.TrUser u')
//			->offset($beginIndex)
//			->limit($rowCount);
//	
//	$journalArr = $query->execute();
//	$conn->beginTransaction();
//	foreach ($journalArr as $journal)
//	{
//		$jId = $journal->id;
//		
//		$user = $journal->TrUser;
//	    $uId = $user->id;
//	    $uTel = $user->telephone;
//	    $uPwd = $user->password;
//		
//	    $pid1 = $places[rand(0, $placeCot-1)]['id'];
//		$pid2 = $places[rand(0, $placeCot-1)]['id'];
//		$pid3 = $places[rand(0, $placeCot-1)]['id'];
//		
//	    Models_Api_User_PRpcLogin::login($uTel, $uPwd);
//	    
//	    Models_Api_Journal_PRpcJournalMethod::createJournalPlace($jId, $pid1);
//	    Models_Api_Journal_PRpcJournalMethod::createJournalPlace($jId, $pid2);  
//	    Models_Api_Journal_PRpcJournalMethod::createJournalPlace($jId, $pid3);       
//	}
//	$conn->commit();
//	
//	$beginIndex += $rowCount;
//}while (count($journalArr) >= $rowCount);

////comment journal place
//Models_Api_User_PRpcLogin::login('123456', '123');
//$query = Doctrine_Query::create()
//		->select('jp.id')
//		->from('TrJournalPlace jp');
//$jpArr = $query->execute();
//$conn->beginTransaction();
//foreach ($jpArr as $jp)
//{
//	$jpId = $jp->id;
//	for ($cot =0 ; $cot < 10 ; $cot++)
//	{
//		Models_Api_Journal_PRpcJournalMethod::commentJournalPlace($jpId, $jpId.'-'.$cot.'-'.'游记景点comment');
//	}
//}
//$conn->commit();


//////journalInfo**********journalInfo**********journalInfo**********journalInfo**********
//$beginIndex = 0;
//$rowCount = 30;	
//do
//{
//	$query = Doctrine_Query::create()
//			->select('jp.id ,j.id,u.id, u.telephone , u.password')
//			->from('TrJournalPlace jp')
//			->leftJoin('jp.TrJournal j')
//			->leftJoin('j.TrUser u')
//			->offset($beginIndex)
//			->limit($rowCount);
//	$jpArr = $query->execute();
//	
//	$infoTextArr = array(
//		'观赏香港夜景的最佳地点是太平山顶，在这里香港各区的繁荣程度一目了然：维多利亚港湾两岸灯光璀璨如银河；港岛这边，从西到东，市中区、湾仔、铜锣湾 、旺角……像一条星光带铺过去；九龙那边，从南到北，尖沙嘴、油麻地、旺角……又一条星光带铺开来。川流不息的车灯光影也值得一看',
//		'说起各地的夜生活，曾有人这样评价：北京最文化，上海最小资，武汉最草根，长沙最娱乐，成都最休闲，广州最物质。对于广州的夜生活，网上也盛传有“六大名牌”：珠江夜游、白云晚望、长隆看马戏、白鹅潭匍吧消夜、天河CBD白领圈狂欢、上下九北京路休闲购物。这一归纳是否偏颇不太好说，但对广州人而言，真正美妙的一天确是从日落开始的'
//	);
//	$conn->beginTransaction();
//	foreach ($jpArr as $jp)
//	{
//		$jpId = $jp->id;
//		
//		$uId = $jp->TrJournal->TrUser->id;
//		$uTel = $jp->TrJournal->TrUser->telephone;
//		$uPwd = $jp->TrJournal->TrUser->password;
//		
//		Models_Api_User_PRpcLogin::login($uTel, $uPwd);
//		
//		
//		foreach ($infoTextArr as $infoText)
//		{
//			Models_Api_Journal_PRpcJournalMethod::addJournalPlaceInfo($jpId, $infoText);
//		}
//	}
//	$conn->commit();
//
//	$beginIndex += $rowCount;
//}while (count($jpArr) >= $rowCount);
//
////////journalInfoPhoto**************journalInfoPhoto**************journalInfoPhoto**************journalInfoPhoto**************
//$dir = 'D:\avatar';
//$avatarDir = dir($dir);
//$photoArr = array();
//while (null != ($file = $avatarDir->read()))
//{
//	$fileDir = "$dir\\$file";
//	if (is_file($fileDir))
//	{
//		array_push($photoArr, base64_encode(file_get_contents($fileDir)));
//	}
//}
//$photoCot = count($photoArr);
//
//$beginIndex = 0;
//$rowCount = 10;	

do 
{
	$query = Doctrine_Query::create()
			->select('jpi.id , jp.id , j.id , u.telephone , u.password')
			->from('TrJournalPlaceInfo jpi')
			->leftJoin('jpi.TrJournalPlace jp')
			->leftJoin('jp.TrJournal j')
			->leftJoin('j.TrUser u')
			->offset($beginIndex)
			->limit($rowCount);
	$jpiArr = $query->execute();
	
	$conn->beginTransaction();
	foreach ($jpiArr as $jpi)
	{
		$jpiId = $jpi->id;
		$uId = $jpi->TrJournalPlace->TrJournal->TrUser->id;
		$uTel = $jpi->TrJournalPlace->TrJournal->TrUser->telephone;
		$uPwd = $jpi->TrJournalPlace->TrJournal->TrUser->password;
		
		Models_Api_User_PRpcLogin::login($uTel, $uPwd);
	
		$cot = 0 ;
		while ($cot++ < 2)
		{
			$photoStr = $photoArr[rand(0, $photoCot-1)];
			$photoTitle = '照片就是了';
		
			Models_Api_Journal_PRpcJournalMethod::addPhotoToJournalInfo($jpiId,$photoTitle, $photoStr);
		}
	}
	$conn->commit();
	
	$beginIndex += $rowCount;
}while (count($jpiArr) >= $rowCount);


