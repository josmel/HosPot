<?php

class Default_Model_Ubigeo extends Core_Model
{
    protected $_tableUbigeo; 
    
    public function __construct() {
        parent::__construct();
        $this->_tableUbigeo = new Application_Model_DbTable_Ubigeo();
    }
    
    static function getRow($id){ 
        $obj= new Application_Entity_RunSql(Application_Model_DbTable_Ubigeo::NAMETABLE, 'flagactive = 1');
        $obj->getone= $id;
        $table= $obj->getone;
        $tbl= (count($table)>0)?$table:false;
        return $tbl;
    }
    
    public function getAllState($idCountry = 1)
    {
        $select = $this->_tableUbigeo->getAdapter()->select()
                ->from(array('u' => $this->_tableUbigeo->getName()), array(
                    'u.id','u.name'
                ))
                ->where('u.idcountry =?', $idCountry)
                ->where('u.idstate !=?', 0)
                ->where('u.idprovince =?', 0)
                ->where('u.iddistrict =?', 0)
                ->where('u.flagactive =?', 1)
                ->query()
                ;

        $result = $select->fetchAll();

        $select->closeCursor();
        return $result;
    }
    
    public function getAllCountry()
    {
        $select = $this->_tableUbigeo->getAdapter()->select()
                ->from(array('u' => $this->_tableUbigeo->getName()), array(
                    'u.id','u.name'
                ))
                ->where('u.idstate =?', 0)
                ->where('u.idprovince =?', 0)
                ->where('u.iddistrict =?', 0)
                ->where('u.flagactive =?', 1)
                ->query()
                ;
        $result = $select->fetchAll();
        $select->closeCursor();
        return $result;
    }
    
    public function listStateJson($idCountry = 1){ 
        $data= $this->getAllState($idCountry);
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
    
    public function listCountryJson(){
        $data= $this->getAllCountry();
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

