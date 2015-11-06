<?php

class Application_Model_DbTable_Ubigeo extends Core_Db_Table {

    protected $_name = "cn_ubigeo";
    protected $_primary = "id";
    const NAMETABLE = "ubigeo";
    const PREFIX = 'u';

    static function populate($params) {
        $data = array(
            'code' => isset($params['code']) ? $params['code'] : '',
            'idcountry' => isset($params['idcountry']) ? $params['idcountry'] : '',
            'idstate' => isset($params['idstate']) ? $params['idstate'] : '',
            'idprovince' => isset($params['idprovince']) ? $params['idprovince'] : '',
            'iddistrict' => isset($params['iddistrict']) ? $params['iddistrict'] : '',
            'name' => isset($params['name']) ? $params['name'] : '',
            'latitude' => isset($params['latitude']) ? $params['latitude'] : '',
            'longitude' => isset($params['longitude']) ? $params['longitude'] : '',
            'lastupdate' => date('Y-m-d H:i:s'));
        $data = array_filter($data);
        $data['paidads'] = isset($params['paidads']) ? $params['paidads'] : 0;
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
            new Zend_Db_Expr('SQL_CALC_FOUND_ROWS '.self::PREFIX.'.id'),
        );
    }
    
    public function columns()
    {
        return array(
            'id',
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
//            array(
//                array("es"=>"emp_sch"),
//                "es.idworkorder = ".self::PREFIX.".idworkorder",
//                null
//            )
        );
    }

}
