<?php

class RegisterController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }
	
    public function registerAction()
	{
		$registerInfo = $_POST['registerInfo'];
		$username = $_POST['username'];
		$password = $_POST['password'];
		$result = null;
		
		$result = Models_Api_User_PRpcRegister::register($registerInfo, $username, $password);
		print_r($result);
	}
    
	public function confirmemailAction()
    {
    	$verifycode = $_GET['verifycode'];
	    
    	$result = Models_Api_User_PRpcRegister::confirmEmail($verifycode);
    	print_r( $result);
    }
    public function confirmphoneAction()
    {
    	$verifycode = $_GET['verifycode'];

    	$result = Models_Api_User_PRpcRegister::confirmTelephoneByCode($verifycode);
    	print_r( $result);
    }
}

