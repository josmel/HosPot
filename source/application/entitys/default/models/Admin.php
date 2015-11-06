<?php

class Default_Model_Admin extends Core_Model
{
    protected $_tableAdmin; 
    
    public function __construct() {
        $this->_tableAdmin = new Application_Model_DbTable_Admin();
    }
    
    static function uniqueEmail($email)
    {
         $user=new Application_Entity_RunSql('Admin',"email='".$email."' and flagactive = 1");
         $user->listed=array();
         $existEmail=$user->listed;
         if(!empty($existEmail)){
             throw new Exception('Email duplicate');
         }
         return $email;         
    }
}

