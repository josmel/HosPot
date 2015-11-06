<?php

class Default_Model_Billboard extends Core_Model
{
    protected $_tableBillboard; 
    
    public function __construct() {
        parent::__construct();
        $this->_tableBillboard = new Application_Model_DbTable_Billboard();
    }
    
    static function getRow($id){
        $obj= new Application_Entity_RunSql(Application_Model_DbTable_Billboard::NAMETABLE, 'flagactive = 1');
        $obj->getone= $id;
        $table= $obj->getone;
        $tbl= (count($table)>0)?$table:false;
        return $tbl;
    }
}

