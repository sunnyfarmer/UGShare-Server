<?php
/**
 * 
 * 直接从服务商的网站拿下来的发送函数
 * @param string $myusernmae
 * @param string $mypassword
 * @param string $telephone
 * @param string $message
 */
function sendsms ($myusernmae, $mypassword, $telephone, $message)
{
    //调用SMSGATE.CN接口发送短信（您的用户名，密码，接收短信的手机号码集，短信内容）
    //返回0或者负数表示发送失败，正数表示已成功发送出去的短信数量
    //本函数版本号：v0.0.2
    if (strlen(rawurlencode("码")) > 7) {
        $URL = "utf";
    } else {
        $URL = "gb";
    }
    $URL = "http://www.smsgate.cn/" . $URL;
    $URL = $URL . ".asp?usr=" . rawurlencode($myusernmae) . "&pwd=" .
     rawurlencode($mypassword);
    $URL = $URL . "&tel=" . rawurlencode($telephone) . "&msg=" .
     rawurlencode($message);
    $content = @file_get_contents($URL);
    if (! is_numeric($content)) {
        $content = 0;
    }
    return (int) $content;
}

class Models_CommonAction_PSms
{
	/**
	 * 
	 * 发送短信，并返回发送成功的短信数
	 * @param unknown_type $telephoneArr
	 * @param unknown_type $message
	 */
	public static function sendsmsBySmsGate($telephoneArr , $message)
	{
		$username = Models_Core::SMS_SMSGATE_USERNAME;
		$password = Models_Core::SMS_SMSGATE_PWD;
		
		$telephones = '';
		if (is_array($telephoneArr))
		{
			foreach ($telephoneArr as $tele)
			{
				$telephones .= "$tele;";
			}
			$telephones = rtrim($telephones , ';');
		}
		elseif (is_int($telephoneArr) || is_string($telephoneArr))
		{
			if (Models_CommonAction_PDataJudge::isTelephone($telephoneArr))
			{
				$telephones = $telephoneArr;
			}
		}
		
		$result = sendsms($username, $password, $telephones, $message);
		return $result;
	}
	
	
}

