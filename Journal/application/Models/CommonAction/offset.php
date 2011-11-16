<?php

/**
 * 范围
 * 73-134 经度
 * 18-53 纬度
 */
/**
 * 9813676 是文件大小除以8
 * 所以max数据是9813675
 * 
 * 
 */


/*
   ���빦�ܣ�����0.01����У����ļ�����γ��ƫ�ơ�
*/
header("Content-Type:text/html; charset=utf-8");
define('__dat_db__' , 'offset.dat' );// DAT����ļ�
define('datmax' , 9813675);// �������-�����¼
//SELECT * FROM `offset_data` where lon=7350 and lat=3930
// # xz.php?lat=39.914914&lon=116.460633 
$lon=$_GET['lon'];
$lat=$_GET['lat'];
$tmplon=intval($lon * 100);
$tmplat=intval($lat * 100);
//���ȵ�����Xֵ
function lngToPixel($lng,$zoom) {
return ($lng+180)*(256<<$zoom)/360;
}
//����X������
function pixelToLng($pixelX,$zoom){
return $pixelX*360/(256<<$zoom)-180;
}
//γ�ȵ�����Y
function latToPixel($lat, $zoom) {
$siny = sin($lat * pi() / 180);
$y=log((1+$siny)/(1-$siny));
return (128<<$zoom)*(1-$y/(2*pi()));
}
//����Y��γ��
function pixelToLat($pixelY, $zoom) {
$y = 2*pi()*(1-$pixelY /(128 << $zoom));
$z = pow(M_E, $y);
$siny = ($z -1)/($z +1);
return asin($siny) * 180/pi();
}

function xy_fk( $number ){
        $fp = fopen(__dat_db__,"rb"); //��1��.�� r ��Ϊ rb
        $myxy=$number;#"112262582";
        $left = 0;//��ʼ��¼
        $right = datmax;//�����¼
        
        //�����ö��ַ������Ҳ����
        while($left <= $right){
            $recordCount =(floor(($left+$right)/2))*8; //ȡ��
            //echo "���㣺left=".$left." right=".$right." midde=".$recordCount."<br />";
            @fseek ( $fp, $recordCount , SEEK_SET ); //�����α�
            $c = fread($fp,8); //��8�ֽ�
            $lon = unpack('s',substr($c,0,2));
            $lat = unpack('s',substr($c,2,2));
            $x = unpack('s',substr($c,4,2));
            $y = unpack('s',substr($c,6,2));
            $jwd=$lon[1].$lat[1];
            //echo "�ҵ��ľ�γ��:".$jwd;
            if ($jwd==$myxy){
               fclose($fp);
               return $x[1]."|".$y[1];
               break;
            }else if($jwd<$myxy){
               //echo " > ".$myxy."<br />";
               $left=($recordCount/8) +1;
            }else if($jwd>$myxy){
               //echo " < ".$myxy."<br />";
               $right=($recordCount/8) -1;
            }
        }
        fclose($fp);
}
$offset =xy_fk($tmplon.$tmplat);
$off=explode('|',$offset);
$lngPixel=lngToPixel($lon,18)-$off[0];
$latPixel=latToPixel($lat,18)-$off[1];
echo pixelToLat($latPixel,18).",".pixelToLng($lngPixel,18);
?>
