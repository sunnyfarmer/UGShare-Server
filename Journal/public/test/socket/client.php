<?php
//以下代码为客户端，它将发送信息并读取回复
send_message('127.0.0.1','85','message to send...');
//自定义函数，发送信息
function send_message($ipserver,$portserver,$message)
{
  $fp=stream_socket_client("tcp://$ipserver:$portserver", $errno, $errstr);
  if(!$fp)
  {
    echo "erreur : $errno - $errstr<br />n";
  }
  else
  {
    fwrite($fp,"$message");
    $response =  fread($fp, 4);
    if($response != "okn")
    {
      echo "the command couldn't be executed...ncause :".$response;
    }
    else
    {
      echo 'execution successfull...';
    }
    fclose($fp);
  }
}