<?php
//$fp = fsockopen("http://127.0.0.1/Journal/public/test/", 80, $errno, $errstr, 30);
//if (!$fp) {
//    echo "$errstr ($errno)<br />\n";
//} else {
//    $out = "GET / HTTP/1.1\r\n";
//    $out .= "Host: http://127.0.0.1/Journal/public/test/\r\n";
//    $out .= "Connection: Close\r\n\r\n";
//    fwrite($fp, $out);
//    while (!feof($fp)) {
//        echo fgets($fp, 128);
//    }
//    fclose($fp);
//}

//http://localhost/Journal/public/test/test.php
echo microtime();
$curPath = dirname(__FILE__);
$file = realpath($curPath.'/../test.php');
$cmd = "php -f $file";//"php -r \"echo 'ok';\"";
echo "\n$cmd\n";
$result = shell_exec($cmd);
echo "\n$result\n";

echo microtime();
