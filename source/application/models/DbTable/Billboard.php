<?php

class Application_Model_DbTable_Billboard extends Core_Db_Table {

    protected $_name = "cn_billboard";
    protected $_primary = "id";
    const NAMETABLE = "billboard";
    const PREFIX = 'b';

    static function populate($params) {
        $data = array(
            'idsubsidiary' => isset($params['idsubsidiary']) ? $params['idsubsidiary'] : '',
            'idmovie' => isset($params['idmovie']) ? $params['idmovie'] : '',
            'schedule3dsubtitle' => isset($params['schedule3dsubtitle']) ? $params['schedule3dsubtitle'] : '',
            'schedule3ddubbing' => isset($params['schedule3ddubbing']) ? $params['schedule3ddubbing'] : '',
            'schedulesubtitle' => isset($params['schedulesubtitle']) ? $params['schedulesubtitle'] : '',
            'scheduledubbing' => isset($params['scheduledubbing']) ? $params['scheduledubbing'] : '',
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
            new Zend_Db_Expr('SQL_CALC_FOUND_ROWS '.self::PREFIX.'.id'),self::PREFIX.'.schedule3dsubtitle',self::PREFIX.'.schedule3ddubbing',
            self::PREFIX.'.schedulesubtitle',self::PREFIX.'.scheduledubbing','m.name as movie_name',"concat(c.name,' - ',s.name) as subsidiary_company_name",
            'g.name as genre_name', 'm.picture as movie_picture', "date_format(m.datepublication, '%d/%m/%Y') as movie_datepublication",
            'c.name as company_name','s.name as subsidiary_name'
        );
    }
    
    public function columns()
    {
        return array(
            'movie_picture', 'movie_name', 'genre_name', 'schedule3dsubtitle', 'schedule3ddubbing', 
            'schedulesubtitle', 'scheduledubbing', 'movie_datepublication', 'company_name','subsidiary_name',
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
                array("m"=>"cn_movie"),
                "m.id = ".self::PREFIX.".idmovie",
                null
            ),
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
                array("g"=>"cn_genre"),
                "g.id = m.idgenre",
                null
            ),
        );
    }

}
