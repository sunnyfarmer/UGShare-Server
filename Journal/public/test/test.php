<?php
date_default_timezone_set('Asia/Shanghai');
$curPath = dirname(__FILE__);
$bootDir = realpath($curPath . '/../../application/PCommonBoot.php');
require_once $bootDir;
PCommonBoot::init(); //初始化设置

//header('Content-type:image/jpeg');

/**
 * 
 * get the relative path of $childPath base on $parentPath
 * @param string $parentPath	base path(absolute path)
 * @param string $childPath		converting path(absolute path)
 */
function convertAbsoToRela ($parentPath, $childPath)
{
    $relativePath = str_replace($parentPath, "", $childPath);
    return $relativePath;
}

$conn = Models_Core::getDoctrineConn();
$query = Doctrine_Query::create()
	->select('u.id , a.id , a.small')
    ->from('TrAvatar a')
    ->leftJoin('a.TrUser u')
    ->where('u.id = ?', 1);
    
$userAvatar = $query->fetchOne();

if ($userAvatar) {
    $result['STATUS'] = intval(Models_Core::STATE_REQUEST_SUCCESS);
    $avatar = $userAvatar->small;
    $avatarId = $userAvatar->id;

    $photoUrl = Models_CommonAction_PPhoto::saveToTemp($avatarId, $avatar);
    
	$result['DATA']['AVATARURL'] = $photoUrl;
} else {
    $result['STATUS'] = intval(Models_Core::STATE_DATA_GETUSERSMALLAVATAR_USERID_INVALID);
}

print_r($result);
