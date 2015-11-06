<?php

class Default_Model_Subsidiary extends Core_Model
{
    protected $_tableSubsidiary; 
    
    public function __construct() {
        parent::__construct();
        $this->_tableSubsidiary = new Application_Model_DbTable_Subsidiary();
    }
    
    static function getRow($id){
        $obj= new Application_Entity_RunSql(Application_Model_DbTable_Subsidiary::NAMETABLE, 'flagactive = 1');
        $obj->getone= $id;
        $table= $obj->getone;
        $tbl= (count($table)>0)?$table:false;
        return $tbl;
    }
}

