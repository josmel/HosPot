<?php
/*
 * Operations basic for make CRUD of tables
 * @author Marcelo Carranza 
 */
class Application_Entity_RunSql extends Core_Db_Table
{
    /** 
     * nombre tabla
     * @var type string
     */   
    protected  $_name ;
    /**
     * name stringQueyr
     * @var type string
     * extension para la consulta de select *
     */
    protected  $_stringQuery;
    
    protected  $_join;
    
    protected $_orderBy='';
    
    protected $_fields=array('*');
    
    /**
     * array('nameCamp'=>'value','nameCamp2'=>'value2',...)
     */
    const SAVE = 'save';
    /**
     * array('nameCamp'=>'value','nameCamp2'=>'value2',...)
     */
    const EDIT = 'edit';
    const LISTED = 'listed';
    const GETONE = 'getone';
    /**
     * array('id'=>array('idtable'=>'value'))
     */
    const DELETE = 'erase';  
    /**
     * Colección de operacion sql (insertar, update,listar,delete,getone)
     * @var type array
     */
    private $_operations = array('save','edit','listed','getone','erase');
    /**
     * Localización de los datos sobrecargados
     * @var type array
     */
    private $data = array();
    
    private $_objTable;
    
    public function __construct($nameTable,$stringQuery=null, $join = null, array $fields = array('*'))
    {    
       $this->_name=$nameTable; 
       $this->_stringQuery=$stringQuery;
       $this->_join=$join;
       $this->_objTable=$this->factoryTable();
       $this->_fields=$fields;
//       var_dump($this->_fields);exit;
    }
    
    public function __set($name,$value=null)
    {
        if(in_array($name,$this->_operations))
        {          
            $objTable=$this->_objTable; 
            $id=$objTable->getPrimaryKey();  
            
            switch ($name) {
                case self::SAVE :    
                    $objTable->insert($objTable->populate($value));                    
                    $this->data[$name]=$objTable->getAdapter()->lastInsertId();                    
                    break;
                case self::DELETE :
                     if(!empty($this->_stringQuery)){
                          $where=$objTable->getAdapter()->quoteInto(
                              $this->_stringQuery,$value);
                     }else{
                         $where=$objTable->getAdapter()->quoteInto($id.'=?',$value);
                     }                                        
                    $objTable->delete($where);
                    break;
                case self::LISTED :
                    $smt = $objTable->select();
                    if(count($this->_fields)>0) $smt = $objTable->getAdapter()->select()->from(strtolower($this->_name),$this->_fields);
                    
                    if(!empty($this->_join)){
                        $smt->setIntegrityCheck(false);
                        $smt->from(array('ac' => $this->_name),
                                $this->_fields);
                        $smt->join(array('a' => 'Application'),
                                'a.idApplication=ac.idApplication', array('nameApplication' => 'a.name'));
                        $smt->join(array('c' => 'Company'),
                                'c.idCompany=ac.idCompany', array('nameCompany' => 'c.name', 'descript', 'c.latitude', 'c.longitude'));
                    } 
                    if(!empty($this->_stringQuery)){
                        $smt->where($this->_stringQuery);
                    } 
                    if(!empty($this->_orderBy)){
                        $smt->order($this->_orderBy);
                    }
                
                    $smt=$smt->query();
                    $result = $smt->fetchAll();                  
                    $smt->closeCursor();
                    $this->data[$name]=$result;
                    break;
                case self::EDIT :          
                    $where=$objTable->getAdapter()->quoteInto($id.' =?',$value[$id]);                       
                    $objTable->update($objTable->populate($value),$where);
                    break;              
                case self::GETONE : 
                    $where=$objTable->getAdapter()->quoteInto($id.' =?',$value); 
                    $smt = $objTable->select()
                            ->where($where)
                            ->query();
                    $result = $smt->fetch();
                    $smt->closeCursor();
                    $this->data[$name]= $result;
                    break;
                default:                    
                    $this->data[$name] = null ;
                    break;
            }
        }
    }
       
    public function getStringQuery(){
        return $this->_stringQuery;
    }
    public function __get($name) 
    { 
        return $this->data[$name];
    }
    
    public function setOrderBy($string){
        $this->_orderBy=$string;
    }
    
    public function factoryTable()
    {         
        $strinNameTable='Application_Model_DbTable_'.$this->_name;
        return $objTable = new $strinNameTable();
    }

    
    public function getPK()
    {
        return $this->_objTable->getPrimaryKey();
    }
    
//    public function __destruct() {
//        $this->_objTable;
//         print "Destruyendo " .  $this->_name . "\n";
//    }
}
