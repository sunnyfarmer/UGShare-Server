<?php
while(true)
{
  //每5秒执行一次
  receive_message('127.0.0.1','85',5);
}
//自定义函数用于获取消息
function receive_message($ipserver,$portnumber,$nbsecondsidle)
{
  //创建socket
  $socket=stream_socket_server('tcp://'.$ipserver.':'.$portnumber, $errno, $errstr);
  if(!$socket)
  {
    //如果创建socket失败输出内容
    echo "$errstr ($errno)<br />n";
  }
  else
  {
    //如果创建成功则接受socket连接并获取信息
    while(null != ($conn=@stream_socket_accept($socket,$nbsecondsidle)))
    {
      $message=fread($conn,1024);
      echo 'i have received that : '.$message;
      fputs ($conn, "ok");
      fclose ($conn);
    }
    fclose($socket);
  }
}
//server结束