<?php
/*
 * Send Mail
 * @author marrselo
 */
//Default_Model_Friend
class Core_Service_Mailing_Mail
{    
    private $_typeMail;
    protected $_transport;
    protected $_zendMail;

    /**
     * 
     * @param Application_Entity_InterfaceMail $typeMail objeto del tipo de envio 
     */
    public function  __construct(Mail_Model_InterfaceMail $typeMail)
    {                
        $this->_transport= self::configMail();
        $this->_typeMail=$typeMail;        
    }
    public function send($configMail){
        $msg=$this->_typeMail->sendMessage($this->_transport,$configMail);
        return $msg;
    }
    
    private static function configMail() 
    {       
        $mail=Zend_Registry::get('mail');
        $host=$mail['host'];
        $config=$mail['config'];   
        return new Zend_Mail_Transport_Smtp($host,$config);
        
    }
    

}

