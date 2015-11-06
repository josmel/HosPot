<?php
/*
 * Send Push
 * @author marrselo
 */
//Default_Model_Friend
class Core_Service_Pusher_Push
{    
    private $_typePush;
    protected $_config;
    
    /**
     * 
     * @param Core_Service_Pusher_Interface $typePush objeto del tipo de envio 
     */
    public function  __construct(Core_Service_Pusher_InterfacePush $typePush)
    {                
        $this->_config= self::configPush();
        $this->_typePush=$typePush;        
    }
    public function send($dataPush){
        $msg=$this->_typePush->sendMessage($dataPush,$this->_config);
        return $msg;
    }
    
    private static function configPush() 
    {       
        return  Zend_Registry::get('push');

    }

}

