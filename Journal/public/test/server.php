<?php
date_default_timezone_set('Asia/Shanghai');
$curPath = dirname(__FILE__);
$bootDir = realpath($curPath . '/../../application/PCommonBoot.php');
require_once $bootDir;
PCommonBoot::init(); //initial setting
//open service
$server = Models_Api_PXmlrpc::getSingleton();//get the object of the server

////////////////////////login
//$request1 = new Zend_XmlRpc_Request('user.login', array('123456', '123'));			//1
//////////$request1 = new Zend_XmlRpc_Request('user.login', array('1310626859', '123'));		//2
//////$request1 = new Zend_XmlRpc_Request('user.login', array('1310710409', '123'));		//3
//////////////////////////$request1 = new Zend_XmlRpc_Request('user.login', array('1307414751', '123'));			//5
////////////////////////$request1 = new Zend_XmlRpc_Request('user.login', array('1307414784', '123'));			
//$r1 = $server->work($request1);			

////register by email
//$email = 'brother0002@ugshare.com';
//$pwd = '123';
//$request1 = new Zend_XmlRpc_Request('user.register', array($email , $pwd));
//$r1 = $server->work($request1);		

//////confirm email register
//$code = '7eef01d0fde77236b2f4f562f752d70d';
//$request1 = new Zend_XmlRpc_Request('user.confirmEmail', array($code));
//$r1 = $server->work($request1);		

//////register by telephone
//$registerInfo = '13810306678';
//$username = 'kb';
//$password = '123';
//$request1 = new Zend_XmlRpc_Request('user.register', array($registerInfo , $username , $password));
//$r1 = $server->work($request1);		

////confirm telephone register by verifycode
//$verifyCode = '120a339fccaf6bfc77bfeb2a3ae49883';
//$request1 = new Zend_XmlRpc_Request('user.confirmTelephoneByCode', array($verifyCode));
//$r1 = $server->work($request1);	

////confirm telephone register by msg
//$telephone = '13810306678';
//$content = 'y';//'Y' 'yes' 'Yes' 'YES'
//$request1 = new Zend_XmlRpc_Request('user.confirmTelephoneByMsg', array($telephone , $content));
//$r1 = $server->work($request1);	


//////////////////get friend movement
//$bObliged = true;
//$beginIndex = 0;
//$rowCount = 500;
//$bRefresh = true;
//$rBeginIndex = 0 ; 
//$rRowCount = 10;
//$request2 = new Zend_XmlRpc_Request('journal.getFriendMovement', array($bObliged , $rBeginIndex , $rRowCount , $bRefresh , $beginIndex , $rowCount));
//$r2 = $server->work($request2);
//
////////create journal
//$title = '游记一下2132';
//$request2 = new Zend_XmlRpc_Request('journal.createJournal', array($title , false));
//$r2 = $server->work($request2);
//$title = '游记2132';
//$request3 = new Zend_XmlRpc_Request('journal.createJournal', array($title , true));
//$r3 = $server->work($request3);
//
//////create journal place
//$conn = Models_Core::getDoctrineConn();
//$query = Doctrine_Query::create()
//		->select('p.id')
//		->from('TrPlace p');
//$placeArr = $query->execute();
//foreach ($placeArr as $place)
//{
//	$pId = $place->id;
//	$journalId = '3';
//	$place = $pId;//'32';
//	$request2 = new Zend_XmlRpc_Request('journal.createJournalPlace' , array($journalId , $place));
//	$r2 = $server->work($request2);
//	$journalId = '4';
//	$place = $pId;
//	$request3 = new Zend_XmlRpc_Request('journal.createJournalPlace' , array($journalId , $place));
//	$r3 = $server->work($request3);
//}
//
//////add journal place info
//$journalPlaceId = '4087';
//$infoText = '这asd里有以偶，哪儿对方说asdf地方';
//$laittude = 36.342342;
//$longitude = 119.243452;
//$request2 = new Zend_XmlRpc_Request('journal.addJournalPlaceInfo' , array($journalPlaceId , $infoText , $laittude , $longitude));
//$r2 = $server->work($request2);
//$laittude = 36.342349;
//$longitude = 119.243460;
//$journalPlaceId = '2162';
//$infoText = '这asd里有以偶，哪儿对方说asdf地方';
//$request3 = new Zend_XmlRpc_Request('journal.addJournalPlaceInfo' , array($journalPlaceId , $infoText , $laittude , $longitude));
//$r3 = $server->work($request3);
//
////add photo to journal info
//$journalInfoId = '6';
//$photoTitle = '么见过帅哥啊';
//$photoContent = base64_encode(file_get_contents('D:\avatar\1.jpg'));
//$request2 = new Zend_XmlRpc_Request('journal.addPhotoToJournalInfo' , array($journalInfoId , $photoTitle , $photoContent));
//$r2 = $server->work($request2);
//
////////favourite journal
//$journalId = '53';
//$comment = '这家伙邪恶';
//$request2 = new Zend_XmlRpc_Request('journal.favouriteJournal' , array($journalId , $comment));
//$r2 = $server->work($request2);
//$journalId = '24';
//$comment = '这家伙邪恶';
//$request3 = new Zend_XmlRpc_Request('journal.favouriteJournal' , array($journalId , $comment));
//$r3 = $server->work($request3);
//
//
//////favourite journal place
//for($cot= 200 ; $cot > 100 ; $cot--)
//{
//	$journalPlaceId = strval($cot);//'3990';
//	$comment = '这家伙邪恶22';
//	$request2 = new Zend_XmlRpc_Request('journal.favouriteJournalPlace' , array($journalPlaceId , $comment));
//	$r2 = $server->work($request2);
//}
//$journalPlaceId = '2';
//$comment = '这家伙邪恶22';
//$request3 = new Zend_XmlRpc_Request('journal.favouriteJournalPlace' , array($journalPlaceId , $comment));
//$r3 = $server->work($request3);

////unfavourite journal
//$journalId = '33';
//$request2 = new Zend_XmlRpc_Request('journal.unFavouriteJournal' , array($journalId));
//$r2 = $server->work($request2);
//
////////unfavourite journalplace
//$jpId = '188';
//$request2 = new Zend_XmlRpc_Request('journal.unFavouriteJornalPlace' , array($jpId));
//$r2 = $server->work($request2);

////add place
//$latitude = 39.9744300;
//$longitude = 116.3032340;
//$name = '艾瑟顿805';
//$request2 = new Zend_XmlRpc_Request('geographic.addPlace' , array($name , $longitude , $latitude));
//$r2 = $server->work($request2);
//$geo = new Models_CommonAction_PGeocoding('艾瑟顿');
//$request2 = new Zend_XmlRpc_Request('geographic.addPlace' , array($geo->getPlaceName_long() , $geo->getLongitude() , $geo->getLatitude()));
//$r2 = $server->work($request2);

////delete place
//$placeId = '89';
//$request2 = new Zend_XmlRpc_Request('geographic.deletePlace' , array($placeId));
//$r2 = $server->work($request2);

//////search place
//$keyWord = '';
//$longitude = 116.303234;
//$latitude = 39.97443;
//$distance = 500;
//$beginIndex = 0;
//$rowCount = 10;
//$request2 = new Zend_XmlRpc_Request('geographic.searchPlace' , array($keyWord , $longitude , $latitude , $distance , $beginIndex , $rowCount));
//$r2 = $server->work($request2);

////////getplaceinfo
//$placeId = '32';
//$request2 = new Zend_XmlRpc_Request('geographic.getPlaceInfo' , array($placeId));
//$r2 = $server->work($request2);

////getCurrentHotCity
//$longitude = 116;
//$latitude = 39;
//$distance = 1000;
//$beginIndex = 0 ; 
//$rowCount = 10;
//$request2 = new Zend_XmlRpc_Request('geographic.getCurrentHotCitys' , array($longitude , $latitude , $distance , $beginIndex , $rowCount));
//$r2 = $server->work($request2);

//////getPlacesByMonth
//$month = 5;
//$longitude = 116;
//$latitude = 39;
//$distance = 100;
//$beginIndex = 0;
//$rowCount =10;
//$request2 = new Zend_XmlRpc_Request('geographic.getPlacesByMonth' , array($month , $longitude , $latitude , $distance , $beginIndex , $rowCount));
//$r2 = $server->work($request2);
//
//////add place tag
//$placeId = '14';
//$tag = '有表演';
//$request2 = new Zend_XmlRpc_Request('geographic.addPlaceTag' , array($placeId , $tag));
//$r2 = $server->work($request2);

//////agree tag
//$placeTagId = '1';
//$request2 = new Zend_XmlRpc_Request('geographic.agreeTag' , array($placeTagId));
//$r2 = $server->work($request2);

//////get journal citys
//$journalId = '3';
//$beginIndex = 0;
//$rowCount = -1;
//$request2 = new Zend_XmlRpc_Request('journal.getJournalCitys' , array($journalId , $beginIndex , $rowCount));
//$r2 = $server->work($request2);

//////get places by tag
//$tag = '森林';
//$longitude = 116.0;
//$latitude = 39.0;
//$distance = 500;
//$beginIndex = 0;
//$rowCount = 10;
//$request2 = new Zend_XmlRpc_Request('geographic.getPlacesByTag' , array($tag , $longitude , $latitude , $distance , $beginIndex , $rowCount));
//$r2 = $server->work($request2);

//////get Places By HotTag
//$tag = '森林';
//$longitude = 116.0;
//$latitude = 39.0;
//$distance = 500;
//$beginIndex = 0;
//$rowCount = 10;
//$request2 = new Zend_XmlRpc_Request('geographic.getPlacesByHotTag' , array($tag , $longitude , $latitude , $distance , $beginIndex , $rowCount));
//$r2 = $server->work($request2);

////////get rim places
//////$usrId = 108;
//$longitude = 116.0;
//$latitude = 39.0;
//$distance = 50000;
//$beginIndex = 0 ; 
//$rowCount= 10;
//$request2 = new Zend_XmlRpc_Request('geographic.getRimPlaces' , array($longitude , $latitude , $distance , $beginIndex , $rowCount));
//$r2 = $server->work($request2);

/////////get favourite journal
//$requestUsrId = '1';
//$beginIndex = 0 ; 
//$rowCount = 10;
//$request2 = new Zend_XmlRpc_Request('journal.getFavouriteJournal' , array($requestUsrId , $beginIndex , $rowCount));
//$r2 = $server->work($request2);

////////get favourite journalplace
//$requestUsrId = '1';
//$beginIndex = 0 ; 
//$rowCount = 200;
//$request2 = new Zend_XmlRpc_Request('journal.getFavouriteJournalPlace' , array($requestUsrId , $beginIndex , $rowCount));
//$r2 = $server->work($request2);

///////////////get journal
//$usrId = 1;
//$requestUserId = '';
//$beginIndex = 0 ; 
//$rowCount = 20;
//$request2 = new Zend_XmlRpc_Request('journal.getJournal' , array($requestUserId , $beginIndex , $rowCount));
//$r2 = $server->work($request2);

/////////getJournalByCity
//$usrId = 107;
//$city = '北京市';
//$beginIndex = 0;
//$rowCount = 10;
//$request2 = new Zend_XmlRpc_Request('journal.getJournalByCity' , array($city , $beginIndex , $rowCount));
//$r2 = $server->work($request2);

////getJournalByPlace
//$usrId = 107;
//$place = '定陵博物馆';
//$beginIndex = 0;
//$rowCount = 10;
//$request2 = new Zend_XmlRpc_Request('journal.getJournalByPlace' , array($place , $beginIndex , $rowCount));
//$r2 = $server->work($request2);

//////get special journal
//$usrId = '107';
//$beginIndex = 0;
//$rowCount = 10;
//$loopRange = 100;
//$request2 = new Zend_XmlRpc_Request('journal.getSpecialJournal' , array( $beginIndex , $rowCount));
//$r2 = $server->work($request2);

////add journal comment
//$journalId = '1836';
//$commentText = '第一个聪明ment';
//$request2 = new Zend_XmlRpc_Request('journal.commentJournal' , array( $journalId ,$commentText));
//$r2 = $server->work($request2);

//////add journal place comment
//$jpId = '3991';
//$commentText = 'comm le ge ment';
//$request2 = new Zend_XmlRpc_Request('journal.commentJournalPlace' , array( $jpId ,$commentText));
//$r2 = $server->work($request2);

//////get journal comment
//$journalId = '3';
//$beginIndex = 0 ; 
//$rowCount = 10;
//$request2 = new Zend_XmlRpc_Request('journal.getJournalComment' , array( $journalId , $beginIndex , $rowCount));
//$r2 = $server->work($request2);

//////get journal place comment
//$journalPlaceId = '3992';
//$beginIndex = 0 ; 
//$rowCount = 10;
//$request2 = new Zend_XmlRpc_Request('journal.getJournalPlaceComment' , array( $journalPlaceId , $beginIndex , $rowCount));
//$r2 = $server->work($request2);

///////delete journal
//$journalId = '1847';
//$request2 = new Zend_XmlRpc_Request('journal.deleteJournal' , array( $journalId));
//$r2 = $server->work($request2);

///////delete journal
//$journalPlaceId = '4088';
//$request2 = new Zend_XmlRpc_Request('journal.deleteJournalPlace' , array( $journalPlaceId));
//$r2 = $server->work($request2);

////delete journal place info
//$infoId = '109';
//$request2 = new Zend_XmlRpc_Request('journal.deleteJournalPlaceInfo' , array( $infoId));
//$r2 = $server->work($request2);

//////delete info photo
//$photoId = '219';
//$request2 = new Zend_XmlRpc_Request('journal.deleteInfoPhoto' , array( $photoId));
//$r2 = $server->work($request2);

//////delete journal comment 
//$commentId = '12975';//12975
//$request2 = new Zend_XmlRpc_Request('journal.deleteJournalComment' , array( $commentId));
//$r2 = $server->work($request2);

//////delete journal comment 
//$commentId = '28366';//28366
//$request2 = new Zend_XmlRpc_Request('journal.deleteJournalPlaceComment' , array( $commentId));
//$r2 = $server->work($request2);

//////set privacy
//$journalId = '1846';
//$privacy = false;
//$request2 = new Zend_XmlRpc_Request('journal.setJournalPrivacy' , array( $journalId , $privacy));
//$r2 = $server->work($request2);

/////get close user
//$longitude = 116.0;
//$latitude = 39.0;
//$distance = 500.0;
//$beginIndex = 0;
//$rowCount = 10;
//$request2 = new Zend_XmlRpc_Request('user.getCloseUser' , array( $longitude , $latitude , $distance , $beginIndex , $rowCount));
//$r2 = $server->work($request2);

//////////get user info
//$requestUser = '';
//$request2 = new Zend_XmlRpc_Request('user.getUserInfo' , array($requestUser));
//$r2 = $server->work($request2);

//////get user big avatar
//$requestUser = '3';
//$request2 = new Zend_XmlRpc_Request('user.getUserBigAvatar' , array($requestUser));
//$r2 = $server->work($request2);

////////get user small avatar
//$requestUser = '3';
//$request2 = new Zend_XmlRpc_Request('user.getUserSmallAvatar' , array($requestUser));
//$r2 = $server->work($request2);

//////get user position
//$requestUser = '1';
//$request2 = new Zend_XmlRpc_Request('user.getUserPosition' , array($requestUser));
//$r2 = $server->work($request2);

//////set user saying
//$saying = 'ok ma 333?';
//$request2 = new Zend_XmlRpc_Request('user.setSaying' , array($saying));
//$r2 = $server->work($request2);

//////set new password
//$oldPwd = 'skyk';
//$newPwd = '123';
//$request2 = new Zend_XmlRpc_Request('user.setNewPassword' , array($oldPwd , $newPwd));
//$r2 = $server->work($request2);

//////follow user by id
//$ids = array('113' , '114');
//$request2 = new Zend_XmlRpc_Request('user.followUserById' , array($ids));
//$r2 = $server->work($request2);

//////follow user by telephone
//$phones = '1307414789';
////$phones = array('1307414774' , '1307414778');
//$request2 = new Zend_XmlRpc_Request('user.followUserByTelephone' , array($phones));
//$r2 = $server->work($request2);

////unfollow user by id
//$userInfo = array('32' , '36');
//$request2 = new Zend_XmlRpc_Request('user.unFollowUserById' , array($userInfo));
//$r2 = $server->work($request2);

//////bind email
//$email = 'skyliang0431@sina.cn';
//$request2 = new Zend_XmlRpc_Request('user.bindEmail' , array($email));
//$r2 = $server->work($request2);

//////bind mobile
//$mobile = '13810306678';
//$request2 = new Zend_XmlRpc_Request('user.bindMobile' , array($mobile));
//$r2 = $server->work($request2);

//////get journal info 
//$jId = '1833';
//$request2 = new Zend_XmlRpc_Request('journal.getJournalInfo' , array($jId));
//$r2 = $server->work($request2);

//////get Journal Info Photo
//$jpi_id = '1';
//$beginIndex = 0 ; 
//$rowCount = 5;
//$request2 = new Zend_XmlRpc_Request('journal.getJournalInfoPhoto' , array($jpi_id , $beginIndex , $rowCount));
//$r2 = $server->work($request2);

///////get JournalPlace Info
//$journalPlaceId = '1';
//$beginIndex = 0 ; 
//$rowCount = 5;
//$request2 = new Zend_XmlRpc_Request('journal.getJournalPlaceInfo' , array($journalPlaceId , $beginIndex , $rowCount));
//$r2 = $server->work($request2);

////////get Journal Places
//$journalId = '11';
//$beginIndex = 0 ; 
//$rowCount = 5;
//$request2 = new Zend_XmlRpc_Request('journal.getJournalPlaces' , array($journalId , $beginIndex , $rowCount));
//$r2 = $server->work($request2);

//////set avatar
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
//$avatar = $photoArr[rand(0, $photoCot-1)];
//$request2 = new Zend_XmlRpc_Request('user.setAvatar' , array($avatar));
//$r2 = $server->work($request2);

///////net calling
//$str = 'testString is it~~';					//////////pass
//$script = "core.getString(\"$str\")";
//date_default_timezone_set('ASIA/SHANGHAI');		//////////pass
//$time = date('c');
//$script = "core.getTime(\"$time\")";
//$number = 7;									//////////pass
//$script = "core.getNumber($number)";
//$array = "[\"aa\" , \"bb\" , \"cc\" , \"dd\"]";	///////////fail,and abandon
//$script = "core.getArray($array)";
//$array = "array('aa' , 'bb' , 'cc' , 'dd')";	//////////fail, and abandon
//$script = "core.getStruct($array)";
//$num = 123;										//////////pass
//$str = 'abc';
//$script = "core.numStr($num,core.getString(\"$str\"))";
//$num = 123;										///////////pass
//$time = date('c');
//$str = 'abc';
//$script = "core.numTimeStr($num,core.getTime(\"$time\"),core.getString(\"$str\"))";
//$arr = "array('aa' , 'bb' , 'cc' , 'dd')"; 		///////////fail, and abandon
//$num = 123;
//$time = date('c');
//$str = 'abc';
//$script = "core.arrNumTimeStr($arr,core.getNumber($num),core.getTime(\"$time\"),core.getString(\"$str\"))";
//$num = 123;										///////////pass
//$str = 'abc';
//$script = "core.getNumber($num);core.getString(\"$str\");";
//$num = 10;										/////////fail 必须有花括号
//$str = 'abc';
//$script = "if(core.getNumber($num) == 10)"
//	."core.getString(\"$str\")";
//$num = 123;										/////////fail 必须有花括号
//$str = 'abc';
//$time = date('c');
//$script = "if(core.getNumber($num) == 10)"
//	."core.getString(\"$str\");"
//	."core.getTime(\"$time\");";
//$num = 123;										/////////wrong use case
//$str = 'abc';
//$time = date('c');
//$script = "if(core.getNumber($num) == 10)"
//	."core.getString(\"$str\")"
//	."core.getTime(\"$time\")";
//$num = 10;										/////////pass
//$str = 'abc';
//$script = "if(core.getNumber($num) == 10){"
//	."core.getString(\"$str\")}";
//$num = 10;										/////////pass
//$str = 'abc';
//$script = "if(core.getNumber($num) == 10 && core.getString(\"$str\") == \"$str\"){"
//	."core.getString(\"$str\")}";
//$num = 123;										/////////pass
//$str = 'abc';
//$time = date('c');
//$script = "if(core.getNumber($num) == 123){"
//	."core.getString(\"$str\");"
//	."core.getTime(\"$time\");"
//	."}";
//$num = 123;										/////////wrong use case
//$str = 'abc';
//$time = date('c');
//$script = "if(core.getNumber($num) == 10){"
//	."core.getString(\"$str\")"
//	."core.getTime(\"$time\")"
//	."}";
//$num = 123;										////////pass
//$str = 'abc';
//$script = "if(core.getNumber($num) == 123){"
//	."core.getString(\"$str\")"
//	."}"
//	."if(core.getNumber($num) >= 5){"
//	."core.getString(\"$str\")"
//	."}";	
//$num = 10;										///////////pass
//$str = 'abc';
//$script = "core.getString(\"$str\");"
//	."for(1:core.getNumber($num)){"
//	."core.getString(rs)}";
//$num = 10;										///////////
//$str = 'abc';
//$script = "for(1:core.getNumber($num)+2)"
//	."core.getString(\"$str\")";
//$num = 10;										///////////
//$str = 'abc';
//$script = "for(1:core.getNumber($num)-2)"
//	."core.getString(\"$str\")";
//$num = 10;										///////////
//$str = 'abc';
//$script = "for(1:core.getNumber($num)*2)"
//	."core.getString(\"$str\")";
//$num = 10;										///////////
//$str = 'abc';
//$script = "for(1:core.getNumber($num)/2)"
//	."core.getString(\"$str\")";
//$num = 10;										///////////pass
//$str = 'abc';
//$script = "for(1:core.getNumber($num)){"
//	."core.getString(\"$str\")}";	
//$num = 10;										///////////pass
//$str = 'abc';
//$time = date('c');
//$script = "for(1:core.getNumber($num)){"
//	."core.getString(\"$str\");"
//	."core.getTime(\"$time\");"
//	."}";	
//$num = 10;										///////////pass
//$str = 'abc';
//$time = date('c');
//$script = "for(1:core.getNumber($num)){"
//	."core.getString(\"$str\")"
//	."core.getTime(\"$time\")"
//	."}";	
//$num = 10;										///////////pass
//$str = 'abc';
//$time = date('c');
//$script = "for(1:core.getNumber($num)){"
//	."core.getString(\"$str\")}"
//	."core.getTime(\"$time\")";		
//$num = 10;										///////////pass
//$str = 'abc';
//$script = "for(1:core.getNumber($num)){"
//	."core.getString(\"$str\")}"
//	."for(1:5){"
//	."core.getString(\"$str\")}";
//	
//	
//$request2 = new Zend_XmlRpc_Request('core.nestcall' , array($script));
//$r2 = $server->work($request2);

//$latitude = 39.01;
//$longitude = 116.01;
//$beginIndex = 0;
//$rowCount = 10;
//$request2 = new Zend_XmlRpc_Request('geographic.getCurAddress' , array($longitude , $latitude , $beginIndex , $rowCount));
//$r2 = $server->work($request2);
//
//$keyWord = '公园';
//$sublocality = '海淀区';
//$beginIndex = 0;
//$rowCount = 10;
//$request2 = new Zend_XmlRpc_Request('geographic.searchPlaceInSublocality' , array($keyWord , $sublocality , $beginIndex , $rowCount));
//$r2 = $server->work($request2);
//
////
//$keyWord = '公园';
//$city = '北京';
//$beginIndex = 0;
//$rowCount = 10;
//$request2 = new Zend_XmlRpc_Request('geographic.searchPlaceInCity' , array($keyWord , $city , $beginIndex , $rowCount));
//$r2 = $server->work($request2);

//$keyWord = '公园';
//$longitude = 116.01;
//$latitude = 39.01;
//$radius = 20.0;
//$beginIndex = 0;
//$rowCount = 10;
//$request2 = new Zend_XmlRpc_Request('geographic.searchPlaceNearBy' , array($keyWord , $longitude , $latitude , $radius , $beginIndex , $rowCount));
//$r2 = $server->work($request2);

//$request2 = new Zend_XmlRpc_Request('journal.getLatestJournal', array());
//$r2 = $server->work($request2);
//
//$photoId = '2';
//$photoVersion = Models_CommonAction_PPhoto::PC_VERSION;
//$request2 = new Zend_XmlRpc_Request('journal.getPhoto', array($photoId, $photoVersion));
//$r2 = $server->work($request2);




