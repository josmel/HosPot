<?php

class Default_Model_Company extends Core_Model
{
    protected $_tableCompany; 
    
    public function __construct() {
        parent::__construct();
        $this->_tableCompany = new Application_Model_DbTable_Company();
    }
    
    static function getRow($id){
        $obj= new Application_Entity_RunSql(Application_Model_DbTable_Company::NAMETABLE, 'flagactive = 1');
        $obj->getone= $id;
        $table= $obj->getone;
        $tbl= (count($table)>0)?$table:false;
        return $tbl;
    }
    
    public function getAll()
    { 
        $select = $this->_tableCompany->getAdapter()->select()
                ->from(array('c'=>$this->_tableCompany->getName()), array(
                    '*'
                ))
                ->where('c.flagactive =?', 1)
                ->query()
                ;
        $result = $select->fetchAll();
        $select->closeCursor();
        return $result;
    }
    
    public function listCompanyJson(){
        $data= $this->getAll();
        $result= array();
        if(count($data)>0)
        {
            $brand= array();
            foreach ($data as $key=>$value){
                if(!in_array($value['id'],$brand)){
                    $brand[]= $value['id'];
                    $result[$value['id']]= array(
                        "name"=> $value['name'],
                        "data"=> array($value)
                    );
                }else{
                    $result[$value['id']]['data'][]= $value;
                }
            }
        }
        return $result;
    }
}

