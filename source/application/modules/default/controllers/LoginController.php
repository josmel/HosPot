<?php

class Default_LoginController extends Core_Controller_ActionDefault  
{
    public function init() {
        parent::init();
        $this->_helper->layout->setLayout('layout-login');
    }
    public function indexAction()
    {
        
    }

    public function lostPasswordAction()
    {
        try {
            if ($this->_request->isPost()){
                
                $email = $this->_getParam('email', null);
                $mail = new Core_Service_Mail(new Mail_Model_Invitation());
                $params = array();
                $params['emailFrom']='no-reply@fullcine.com';
                $params['nameFrom']='Full Cine';
                $params['receptor_name'] = 'Usuario';
                $params['receptor_email'] = $email;
                $params['token'] = md5(uniqid());
                
                $tblAdmin = new Default_Model_Admin();
                $admin = $tblAdmin->getRowEmail($email);
                $id = isset($admin['id'])?$admin['id']:0;
                $objAdmin = new Application_Entity_RunSql('Admin');
                $objAdmin->edit = array($objAdmin->getPK()=>$id, 'rememberpassword' => $params['token']);
                
                $mail->send($params);
            }
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }
    
    public function authAction()
    { 
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        if ($this->_request->isPost()){ 
            $params = $this->_getAllParams();
            if($params['user']!=""&&$params['pass']!=""){
                $login=$this->auth($params['user'],$params['pass']);  
                if($login){
                    $this->_redirect('/index/');
                }
            }
        }
        $this->_redirect('/login');
    }
    
    public function logoutAction() {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_redirect('/');
    }
    
    public function verifyEmailAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        try {
            if ($this->_request->isPost()){
                $email = $this->_getParam('email', null);
                if(empty($email)) throw new Exception('wrong params') ;
                Default_Model_Admin::uniqueEmail($email);
                $rpta = 'true';
            }else{
                throw new Exception('bad request');
            }
        } catch (Exception $exc) {
            $rpta = 'false';
        }
        echo $rpta;
    }
    
    private function authAuto($params)
    {
        $login=$this->auth($params['user'],$params['pass']);  
        if($login){
            $this->_redirect('/index/');
        }else{
            $this->_redirect('/login');
        }
    }
    
}