<?php

class Application_Model_DbTable_Company extends Core_Db_Table {

    protected $_name = "cn_company";
    protected $_primary = "id";
    const NAMETABLE = "company";
    const PREFIX = 'c';

    static function populate($params) {
        $data = array(
            'name' => isset($params['name']) ? $params['name'] : '',
            'picture' => isset($params['picture']) ? $params['picture'] : '',
            'pictureads' => isset($params['pictureads']) ? $params['pictureads'] : '',
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
            new Zend_Db_Expr('SQL_CALC_FOUND_ROWS '.self::PREFIX.'.id'),self::PREFIX.'.picture',self::PREFIX.'.name',
            self::PREFIX.'.paidads',
        );
    }
    
    public function columns()
    {
        return array(
            'picture', 'name', 'paidads',
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
