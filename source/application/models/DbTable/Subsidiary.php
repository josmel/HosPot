<?php

class Application_Model_DbTable_Subsidiary extends Core_Db_Table {

    protected $_name = "cn_subsidiary";
    protected $_primary = "id";
    const NAMETABLE = "subsidiary";
    const PREFIX = 's';

    static function populate($params) {
        $data = array(
            'idcompany' => isset($params['idcompany']) ? $params['idcompany'] : '',
            'idubigeo' => isset($params['idubigeo']) ? $params['idubigeo'] : '',
            'name' => isset($params['name']) ? $params['name'] : '',
            'address' => isset($params['address']) ? $params['address'] : '',
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
            new Zend_Db_Expr('SQL_CALC_FOUND_ROWS '.self::PREFIX.'.id'),self::PREFIX.'.address',self::PREFIX.'.name',
            'u.name as ubigeo_name',
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
                array("u"=>"cn_ubigeo"),
                "u.id = ".self::PREFIX.".idubigeo",
                null
            )
        );
    }

}
