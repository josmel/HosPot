<?php

class Application_Model_DbTable_Movie extends Core_Db_Table {

    protected $_name = "cn_movie";
    protected $_primary = "id";
    const NAMETABLE = "movie";
    const PREFIX = 'm';

    static function populate($params) {
        $data = array(
            'idgenre' => isset($params['idgenre']) ? $params['idgenre'] : '',
            'idubigeo' => isset($params['idubigeo']) ? $params['idubigeo'] : '',
            'name' => isset($params['name']) ? $params['name'] : '',
            'picture' => isset($params['picture']) ? $params['picture'] : '',
            'synopsis' => isset($params['synopsis']) ? $params['synopsis'] : '',
            'imdb' => isset($params['imdb']) ? $params['imdb'] : '',
            'urltrailer' => isset($params['urltrailer']) ? $params['urltrailer'] : '',
            'director' => isset($params['director']) ? $params['director'] : '',
            'cast' => isset($params['cast']) ? $params['cast'] : '',
            'datepublication' => isset($params['datepublication']) ? $params['datepublication'] : null,
            'lastupdate' => date('Y-m-d H:i:s'));
        $data = array_filter($data);
        $data['premiere'] = isset($params['premiere']) ? $params['premiere'] : 0;
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
            self::PREFIX.'.synopsis', "date_format(".self::PREFIX.'.datepublication'.", '%d/%m/%Y') as datepublication" ,self::PREFIX.'.cast',
            'u.name as ubigeo_name', 'g.name as genre_name',
        );
    }
    
    public function columns()
    {
        return array(
            'picture', 'name', 'synopsis', 'genre_name', 'ubigeo_name', 'datepublication',
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
            ),
            array(
                array("g"=>"cn_genre"),
                "g.id = ".self::PREFIX.".idgenre",
                null
            ),
        );
    }

}
