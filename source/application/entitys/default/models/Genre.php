<?php

class Default_Model_Genre extends Core_Model
{
    protected $_tableGenre; 
    
    public function __construct() {
        parent::__construct();
        $this->_tableGenre = new Application_Model_DbTable_Genre();
    }
    
    static function getRow($id){
        $obj= new Application_Entity_RunSql(Application_Model_DbTable_Genre::NAMETABLE, 'flagactive = 1');
        $obj->getone= $id;
        $table= $obj->getone;
        $tbl= (count($table)>0)?$table:false;
        return $tbl;
    }
    
    public function getAll()
    {
        $select = $this->_tableGenre->getAdapter()->select()
                ->from(array('g' => $this->_tableGenre->getName()), array(
                    '*'
                ))
                ->where('g.flagactive =?', 1)
                ->query()
                ;
        $result = $select->fetchAll();
        $select->closeCursor();
        return $result;
    }
    
    public function listJson(){
        $data= $this->getAll();
//        var_dump($data);Exit;
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

