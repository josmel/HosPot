<?php

class Default_Model_Price extends Core_Model
{
    protected $_tablePrice; 
    
    public function __construct() {
        parent::__construct();
        $this->_tablePrice = new Application_Model_DbTable_Price();
    }
    
    static function getRow($id){
        $obj= new Application_Entity_RunSql(Application_Model_DbTable_Price::NAMETABLE, 'flagactive = 1');
        $obj->getone= $id;
        $table= $obj->getone;
        $tbl= (count($table)>0)?$table:false;
        return $tbl;
    }
}

