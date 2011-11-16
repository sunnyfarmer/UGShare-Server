<?php
class Models_CommonAction_PEmail
{
    private $title 		= null; 	//邮件标题
    private $bodyText 	= null; 	//邮件内容
    private $bodyHtml 	= null;		//邮件HTML内容
    private $toList 	= array(); 	//收件人的邮件地址列表
    
    public function __construct ($title = null, $content = null, $toList = null)
    {}
    /**
     * 
     * 添加收件人邮箱地址
     * @param string $toAddress
     */
    public function addTo ($toAddress)
    {
        if (is_null($toAddress) || !is_string($toAddress) || !Models_CommonAction_PDataJudge::isAddress($toAddress)) { //添加的地址参数为空，或不是字符串，那么返回false
            return false;
        }
        array_push($this->toList, $toAddress);
    }
    /**
     * 
     * 发送邮件
     */
    public function send ()
    {
        if (0 >= count($this->toList)) 
        {//收件人列表为空
        	return false;
        }
        if (!$this->title)
        {//邮件标题为空
        	return false;
        }
        
        //设置邮件传送器
        $config = array(
			'auth' 		=> Models_Core::EMAIL_AUTH_MODE,
			'username' 	=> Models_Core::EMAIL_USERNAME,
			'password' 	=> Models_Core::EMAIL_PASSWORD
		);
        $transport = new Zend_Mail_Transport_Smtp(Models_Core::EMAIL_SMTP_SERVER, $config);
    
        //设置邮件属性
        $mail = new Zend_Mail(Models_Core::EMAIL_CHARSET);
	    $mail->setBodyText($this->bodyText);
	    $mail->setBodyHtml($this->bodyHtml);
	    $mail->setFrom(Models_Core::EMAIL_USERNAME, Models_Core::EMAIL_SERVER_ANONYMOUS);
	    $mail->setSubject($this->title);
		foreach ($this->toList as $to)
		{
			$mail->addTo($to , Models_Core::EMAIL_TO_ANONYMOUS);
		}	
	    $mail->send($transport);
    }
	/**
	 * @return the $title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @return the $content
	 */
	public function getBodyText() {
		return $this->bodyText;
	}

	/**
	 * @param field_type $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * @param field_type $content
	 */
	public function setBodyText($content) {
		$this->bodyText = $content;
	}
	/**
	 * @return the $bodyHtml
	 */
	public function getBodyHtml() {
		return $this->bodyHtml;
	}

	/**
	 * @param field_type $bodyHtml
	 */
	public function setBodyHtml($bodyHtml) {
		$this->bodyHtml = $bodyHtml;
	}

}


/**
 * example to use
 */
//$email = new Models_CommonAction_PEmail();
//$email->setTitle('一二三四五六七八九十first email by this class');
//$email->setBodyText('TextText!!');
//$email->setBodyHtml('
//	<html>
//		<body>
//			<p>
//				一二三四五六七八九十一二三四五六七八九十一二三四五六七八九十 example cannot be edited
//				because our editor uses a textarea
//				for input,
//				and your browser does not allow
//				a textarea inside a textarea.
//			</p>
//			<textarea rows="10" cols="30">
//				The cat was playing in the garden.
//');
//$email->addTo('');
//$email->addTo(null);
//$email->addTo(Models_Core::EMAIL_TO_TEST1);
//$email->addTo(Models_Core::EMAIL_TO_TEST2);
//$email->send();