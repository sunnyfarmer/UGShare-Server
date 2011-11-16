<?php
//短信网关SMSGATE.CN短信接收演示程序
        //本代码中的三处“7FG48HGF76PBFM”是我胡乱设置的校验码，实际使用时应该替换为任意其他字符
        //使用时，需将本段代码保存为一个PHP文件，上传到服务器
        //并在您的短信网关账户中，把您的短信回复通知接口地址设置为：“本页面的URL绝对地址?verifycode=7FG48HGF76PBFM”
        //赋初始值（默认为接收失败，返回“0”）
        $received = 0;
        //比较校验码，检查信息真伪
        if ($_GET["verifycode"] == "77edbd284eac30ea5c2f41d8a5d2b940") {
            //取得各参数数据
            $telephone = $_GET["mob"];
            $locating = $_GET["loc"];
            $messagetext = $_GET["msg"];
            $servicenumber = $_GET["srv"];
            $receivetime = $_GET["tim"];
            //判断各数据的合法性
            if ((is_numeric($telephone)) && ($messagetext != "") && (is_numeric($servicenumber)) && ($locating != "")) {
                //对接收到的数据进行自己的处理，本例为保存到当前目录下的一个文本文件“myreceive.txt”中
                //本页面所在目录须具备文件读写及创建权限
                $fp = fopen("myreceive.txt", "a");
                fwrite($fp, 
                $receivetime . "\t" . $servicenumber . "\t" . $telephone . "\t" .
                 "（" . $locating . "）" . "\t" . $messagetext . "\n");
                fclose($fp);
                //接收成功，返回“1”
                $received = 1;
                 //关闭各条件语句
            }
		}
		
		//输出处理结果代码
		echo $received;