<?php
//function deleteFlode($dir)
//{
//  //先删除目录下的文件：
//  $dh=opendir($dir);
//  while (null != ($file=readdir($dh))) {
//    if($file!="." && $file!="..") {
//      $fullpath=$dir."/".$file;
//      if(!is_dir($fullpath)) {
//          unlink($fullpath);
//      } else {
//          deleteFlode($fullpath);
//      }
//    }
//  }
//  closedir($dh);
//  //删除当前文件夹：
//  if(rmdir($dir)) {
//    return true;
//  } else {
//    return false;
//  }
//}
//function dealMethod($fileDir)
//{
////	echo "$fileDir \n";
//}
//
//function dirDealMethod($fileDir)
//{
////	echo "$fileDir \n";
//	$strArr = explode('\\', $fileDir);
//	$lastStr = $strArr[count($strArr)-1];
////	echo "$lastStr\n";
//	if (strcmp($lastStr, '.svn') == 0)
//	{
//		deleteFlode($fileDir);
//	}
//}
//
//function dealFile($dir , $fileMethod , $dirMethod)
//{
//	//获得路径句柄
//	$avatarDir = dir($dir);
//	
//	//遍历当前层次路径
//	while (null != ($file = $avatarDir->read()))
//	{
//		$fileDir = "$dir\\$file";
//	
//		if (0 == strcmp($file, '.') || 0 == strcmp($file, '..'))
//		{
//			continue;
//		}
//		
//		if (is_dir($fileDir))
//		{
//			dealFile($fileDir, $fileMethod , $dirMethod);
//			$dirMethod($fileDir);
//		}
//		elseif(is_file($fileDir))
//		{
//			$fileMethod($fileDir);
//		}
//	}
//}
//
//$dir = 'C:\Users\samson\Desktop\Journal';//'C:\Users\samson\Desktop\public';
//dealFile($dir, 'dealMethod' , 'dirDealMethod');


echo 'a'."\n";