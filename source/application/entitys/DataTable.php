<?php

/**
 * Description of Querys
 *
 * @author marrselo
 */
class Application_Entity_DataTable {

    protected $_tableName;
    protected $_prefixTable;
    protected $_objTable;
    protected $_columnDisplay;
    protected $_columnSearch;
    protected $_dtwhere = '';
    protected $_dtorwhere = '';
    protected $_where = '';
    protected $_flagActive;
    protected $_primaryKey;
    protected $_numberPage;
    protected $_limit;
    protected $_order = null;
    protected $_dtorder = null;
    protected $_newIcon = null;
    protected $_setIconAction = null;

    public function __construct($nameTable, $limit, $numberPage, $flagActive = true, $column = null) {
        $this->_tableName = $nameTable;
        $this->_objTable = $this->factoryTable();
        if ($column == null) {
            $this->_columnDisplay = $this->getColumnDisplay();
        } else {
            $this->_columnDisplay = $this->getColumnDisplayTwo();
        }
        //$this->_dtwhere=$dtwhere;
        $this->_flagActive = $flagActive;
        $this->_primaryKey = $this->_objTable->getPrimaryKey();
        $this->_numberPage = $numberPage;
        $this->_limit = " LIMIT $limit ";
    }

    public function factoryTable() {
        $strinNameTable = 'Application_Model_DbTable_' . $this->_tableName;
        return $objTable = new $strinNameTable();
    }

    public function setWhere($stringWhere) {
        $this->_where = $stringWhere;
    }

    public function setSearch($sSearch) {

        return $this->_dtwhere = !empty($sSearch) ? $sSearch : '';
    }

    public function setOrder($column, $ascDesc = 'asc') {
        return $this->_order = " order by " . $column . ' ' . $ascDesc . ' ';
    }

    public function getColumnDisplay() {
        return $this->_objTable->columnDisplay();
    }

    public function getColumnDisplayTwo() {
        return $this->_objTable->columnDisplayTwo();
    }

    public function getPrefixTable() {
        $classNameTable = $this->nameClassTable();
        return $classNameTable::PREFIX;
    }

    public function getQuery($displayStart = null, $displayLength = null) {
        if (isset($displayStart) && $displayLength != '-1') {
            $this->_limit = " LIMIT " . intval($displayStart) . ", " .
                    intval($displayLength);
        }
        $whereActive = ($this->_flagActive == true) ? $this->_objTable->getWhereActive() : '';
        $id = $this->_primaryKey;
        $query = "
            SELECT SQL_CALC_FOUND_ROWS " . $id . ", " . str_replace(" , ", " ", implode(", ", $this->_columnDisplay)) . "
            FROM 
            " . strtolower($this->_tableName) . "
            WHERE 1 " . $this->_where . " " . $this->_dtwhere .
                $whereActive
                . $this->_order
                . $this->_limit;
        $smt = $this->_objTable->getAdapter()->query($query);

        $output = array(
            'sEcho' => intval($this->_numberPage),
            'iTotalRecords' => 0,
            'iTotalDisplayRecords' => 0,
            'aaData' => array()
        );
        while ($aRow = $smt->fetch()) {
            $row = array();

            for ($i = 0; $i < count($this->_columnDisplay); $i++) {
                $row[$i] = $aRow[$this->_columnDisplay[$i]];
            }
            if (empty($this->_setIconAction)) {
                $row[$i] = "<a class='icAction icGear' href='javascript:;' data-id='" . $aRow[$id] . "'></a><a href='javascript:;' class='icAction icDelete' title='Eliminar' data-id='" . $aRow[$id] . "'></a>";
                // Add the row ID and class to the object            
            } else {
                $row[$i] = str_replace("__ID__", $aRow[$id], $this->_setIconAction);
            }
            $row['DT_RowId'] = 'row_' . $aRow[$id];
            $output['aaData'][] = $row;
        }
        $total = $smt->getAdapter()->fetchOne('SELECT FOUND_ROWS()');
        $output['iTotalRecords'] = $total;
        $output['iTotalDisplayRecords'] = $total;
        $smt->closeCursor();
        return $output;
    }

    public function getQuery2($displayStart = null, $displayLength = null) { 
        $select = $this->_objTable->getAdapter()->select();
        $this->_prefixTable = $this->getPrefixTable();
        $headTable = array($this->_prefixTable => strtolower('cn_'.$this->_tableName));
        $select->from($headTable, $this->_columnDisplay);
       
        $this->setJoins($select);
        $this->setJoinsLeft($select);
        $this->setWheres($select);
        $this->setFlagActive($select);
        $this->setOrder2($select);
        $this->setLimit($select, $displayStart, $displayLength);
        $query = $select->query();
        

        $result = $query->fetchAll();
        
        $output = array(
            'sEcho' => intval($this->_numberPage),
            'iTotalRecords' => 0,
            'iTotalDisplayRecords' => 0,
            'aaData' => array()
        );
        $viewColumns = $this->_objTable->columns();
        $id = $this->_primaryKey;
        foreach ($result as $key => $obj) {
            $row = array();
            for ($i = 0; $i < count($viewColumns); $i++) {
                $row[$i] = $obj[$viewColumns[$i]];
            }
            if (empty($this->_setIconAction)) {
                $row[$i] = "<a class='icAction icGear' href='javascript:;' data-id='" . $obj[$id] . "'></a><a href='javascript:;' class='icAction icDelete' title='Eliminar' data-id='" . $obj[$id] . "'></a>";
                // Add the row ID and class to the object            
            } else {
                $row[$i] = str_replace("__ID__", $obj[$id], $this->_setIconAction);
            }
            $row['DT_RowId'] = 'row_' . $obj[$id];
            $output['aaData'][] = $row;
        }
        $total = $select->getAdapter()->fetchOne('SELECT FOUND_ROWS()');
        $output['iTotalRecords'] = $total;
        $output['iTotalDisplayRecords'] = $total;
        $query->closeCursor();
        return $output;
    }

    public function setSearch2($arr = null) {
        return $this->_dtwhere = !empty($arr) ? $arr : false;
    }
    
    public function setOrWhere($arr = null) {
        return $this->_dtorwhere = !empty($arr) ? $arr : false;
    }

    public function setJoins($select) {
        $nameClassTable = $this->nameClassTable();
        if (method_exists($nameClassTable, "joinsTable")) {
            $joins = $this->_objTable->joinsTable();
            foreach ($joins as $value) {
                $select->join($value[0], $value[1], $value[2]);
            }
        }
        return $select;
    }
    
    public function setJoinsLeft($select)
    {
        $nameClassTable=$this->nameClassTable();
        if(method_exists($nameClassTable, 'joinsleftTable')){
            $joins= $this->_objTable->joinsleftTable();
            foreach ($joins as $value) {
                $select->joinLeft($value[0], $value[1], $value[2]);
            }
        }
        return $select;
    }

    public function setLimit($select, $displayStart = null, $displayLength = null) {
        $countRows = (isset($displayLength)) ? $displayLength : -1;
        $startRows = (isset($displayStart)) ? $displayStart : 0;
        $select->limit($countRows, $startRows);
        return $select;
    }
    
    public function setOrder2($select) {
        if(!empty($this->_dtorder))
            $select->order($this->_dtorder);
        return $select;
    }
    
    public function setOrderColumn($column, $ascDesc = 'asc') {
        $this->_dtorder = $column . ' ' . $ascDesc;
        return $this->_dtorder;
    }
    
    public function setOrderColumnMultiple(array $column) {
        $this->_dtorder = $column;
        return $this->_dtorder;
    }

    public function setWheres($select) {
        $wheres = $this->_dtwhere;
        if ($wheres) {
            foreach ($wheres as $where) {
                if (is_array($where)) {
                    $select->where($where[0], $where[1]);
                } else {
                    $select->where($where);
                }
            }
        }
        return $select;
    }
    
    public function _setOrWheres($select) {
        $wheres = $this->_dtorwhere;
        if ($wheres) {
            foreach ($wheres as $where) {
                if (is_array($where)) {
                    $select->orWhere($where[0], $where[1]);
                } else {
                    $select->orWhere($where);
                }
            }
        }
        return $select;
    }
    
    public function setFlagActive($select){
        $nameClassTable=$this->nameClassTable();
        if(method_exists($nameClassTable, 'getActive')){
            $whereActive = ($this->_flagActive == true) ? $this->_objTable->getActive() : '';
            if($this->_flagActive == true)
                $select->where($whereActive[0], $whereActive[1]);
        }
        return $select;
    }

    public function setNewIcon($stringHTML) {
        return $this->_newIcon = $stringHTML;
    }

    public function setNameTable($newNameTable) {
        $this->_tableName = $newNameTable;
    }

    public function nameClassTable() {
        return 'Application_Model_DbTable_' . $this->_tableName;
    }

    public function setIconAction($stringHTML) {
        $this->_setIconAction = $stringHTML;
    }

}
