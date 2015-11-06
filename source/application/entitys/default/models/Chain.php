<?php

class Default_Model_Chain extends Core_Model
{
    protected $_tableChain; 
    
    public function __construct() {
        parent::__construct();
        $this->_tableChain= new Application_Model_DbTable_Chain();
    }
    
    static function getRow($id){
        $obj= new Application_Entity_RunSql(Application_Model_DbTable_Chain::NAMETABLE, 'flagactive = 1');
        $obj->getone= $id;
        $table= $obj->getone;
        $tbl= (count($table)>0)?$table:false;
        return $tbl;
    }
}

