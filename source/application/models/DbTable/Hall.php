<?php

class Application_Model_DbTable_Hall extends Core_Db_Table {

    protected $_name = "cn_hall";
    protected $_primary = "id";
    const NAMETABLE = "hall";
    const PREFIX = 'h';

    static function populate($params) {
        $data = array(
            'subsidiary_id' => isset($params['subsidiary_id']) ? $params['subsidiary_id'] : '',
            'row' => isset($params['row']) ? $params['row'] : '',
            'column' => isset($params['column']) ? $params['column'] : '',
            'name' => isset($params['name']) ? $params['name'] : '',
            'lastupdate' => date('Y-m-d H:i:s'));
        $data = array_filter($data);
        $data['flagactive'] = isset($params['flagactive']) ? $params['flagactive'] : 1;
        return $data;
    }

    public function getPrimaryKey() {
        return $this->_primary;
    }

    public function getWhereActive() {
        return " AND flagactive LIKE '1'";
    }

    public function getActive() {
        return array(self::PREFIX.'.flagactive = ?', 1);
    }
    
    public function columnDisplay()
    {
        return array(
            new Zend_Db_Expr('SQL_CALC_FOUND_ROWS '.self::PREFIX.'.id'),self::PREFIX.'.row',self::PREFIX.'.name',
            's.name as subsidiary_name',
        );
    }
    
    public function columns()
    {
        return array(
            'name', 'address', 'ubigeo_name',
        );
    }
    
    public function joinsTable()
    {
        return array(
//            array(
//                array("a"=>"app"),
//                "a.idapp = ".self::PREFIX.".idapp",
//                null
//            ),
        );
    }
    
    public function joinsleftTable()
    {
        return array(
            array(
                array("s"=>"cn_subsidiary"),
                "s.id = ".self::PREFIX.".subsidiary_id",
                null
            )
        );
    }

}
