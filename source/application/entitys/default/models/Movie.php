<?php

class Default_Model_Movie extends Core_Model
{
    protected $_tableMovie; 
    
    public function __construct() {
        parent::__construct();
        $this->_tableMovie = new Application_Model_DbTable_Movie();
    }
    
    static function getRow($id, $formatDate = false){
        $obj= new Application_Entity_RunSql(Application_Model_DbTable_Movie::NAMETABLE, 'flagactive = 1');
        $obj->getone= $id;
        $table= $obj->getone;
        if(count($table)>0){
            if($formatDate){
                $date = explode('-', $table['datepublication']);
                if(count($date)==3){
                    $hour = explode(' ', $date[2]);
                    if(count($hour)==2){
                        $table['datepublication'] = count($date)==3?$hour[0].'/'.$date[1].'/'.$date[0]:false;
                    }
                }
            }
            $tbl = $table;
        }else{
            $tbl = false;
        }
        return $tbl;
    }
    
    public function getAll()
    {
        $select = $this->_tableMovie->getAdapter()->select()
                ->from(array('m' => $this->_tableMovie->getName()), array(
                    '*'
                ))
                ->where('m.flagactive =?', 1)
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

