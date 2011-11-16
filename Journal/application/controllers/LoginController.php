<?php
/**
 * LoginController
 * 
 * @author
 * @version 
 */
require_once 'Zend/Controller/Action.php';
class LoginController extends Zend_Controller_Action
{
    /**
     * The default action - show the home page
     */
    public function indexAction ()
    {
        // TODO Auto-generated LoginController::indexAction() default action
    }
    
    public function loginAction()
    {
    	$loginInfo = $_POST['loginInfo'];
		$password = $_POST['password'];
		$result = null;
		
		$result = Models_Api_User_PRpcLogin::login($loginInfo , $password);
		
		print_r($result);
    }
}
