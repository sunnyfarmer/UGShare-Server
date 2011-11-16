<?php
/**
 * RvcMsg
 * 
 * @author
 * @version 
 */
require_once 'Zend/Controller/Action.php';
class RvcMsgController extends Zend_Controller_Action
{
    /**
     * The default action - show the home page
     */
    public function indexAction ()
    {
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
				Models_Api_User_PRpcRegister::confirmTelephoneByMsg($telephone, $messagetext);	
            }
		}
		
		//输出处理结果代码
		echo $received;
    }
}

