<?php

class Application_Model_DbTable_Price extends Core_Db_Table {

    protected $_name = "cn_price";
    protected $_primary = "id";
    const NAMETABLE = "price";
    const PREFIX = 'pr';

    static function populate($params) {
        $data = array(
            'idsubsidiary' => isset($params['idsubsidiary']) ? $params['idsubsidiary'] : '',
            'name' => isset($params['name']) ? $params['name'] : '',
            'idday' => isset($params['idday']) ? $params['idday'] : '',
            'lastupdate' => date('Y-m-d H:i:s'));
        $data = array_filter($data);
        $data['value'] = isset($params['value']) ? $params['value'] : 0;
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
            new Zend_Db_Expr('SQL_CALC_FOUND_ROWS '.self::PREFIX.'.id'),self::PREFIX.'.value',self::PREFIX.'.name',
            "concat(c.name,' - ',s.name) as subsidiary_company_name", "d.name as day_name",
        );
    }
    
    public function columns()
    {
        return array(
            'name', 'value', 'day_name', 'subsidiary_company_name', 
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
                "s.id = ".self::PREFIX.".idsubsidiary",
                null
            ),
            array(
                array("c"=>"cn_company"),
                "c.id = s.idcompany",
                null
            ),
            array(
                array("d"=>"cn_day"),
                "d.id = ".self::PREFIX.".idday",
                null
            ),
        );
    }

}
