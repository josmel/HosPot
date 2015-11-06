<?php

class Application_Model_DbTable_Admin extends Core_Db_Table {

    protected $_name = "cn_admin";
    protected $_primary = "id";
    const NAMETABLE = "admin";
    const PREFIX = 'a';

    static function populate($params) {
        $data = array(
            'name' => isset($params['name']) ? $params['name'] : '',
            'lastnamepaternal' => isset($params['lastnamepaternal']) ? $params['lastnamepaternal'] : '',
            'lastnamematernal' => isset($params['lastnamematernal']) ? $params['lastnamematernal'] : '',
            'phone' => isset($params['phone']) ? $params['phone'] : '',
            'picture' => isset($params['picture']) ? $params['picture'] : '',
            'email' => isset($params['email']) ? $params['email'] : '',
            'password' => isset($params['password']) ? md5($params['password']) : '',
            'rememberpassword' => isset($params['rememberpassword']) ? md5($params['rememberpassword']) : '',
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
