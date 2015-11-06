<?php


class Default_ChainController extends Core_Controller_ActionDefault
{
    const _TABLECLASS = 'Chain';
    
    public function init() {
        parent::init();
    }
    
    public function preDispatch() {
        parent::preDispatch();
    }
    
    public function indexAction()
    { 
        $objCompany= new Default_Model_Company();
        $company= $objCompany->listCompanyJson();
        $this->addYosonVar('company', Zend_Json_Encoder::encode($company), false);
        $objUbigeo= new Default_Model_Ubigeo();
        $ubigeo= $objUbigeo->listStateJson();
        $this->addYosonVar('ubigeo', Zend_Json_Encoder::encode($ubigeo), false);
    }
    
    public function listAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        try {
            $params=$this->_getAllParams();
            $iDisplayStart=isset($params['start'])?$params['start']:null;
            $iDisplayLength=isset($params['length'])?$params['length']:null;
            $sEcho=isset($params['sEcho'])?$params['sEcho']:null;     
            $search=isset($params['search'])?$params['search']:''; 
            $columns=isset($params['columns'])?$params['columns']:''; 
            $arrSearch=array();
            if(!empty($search['value']))
                $arrSearch[] = array("a.name like ?", '%'.$search['value'].'%');
            $obj=new Application_Entity_DataTable(self::_TABLECLASS,0, $sEcho, true);
            $obj->setSearch2($arrSearch);
            $obj->setIconAction($this->tableIcons());
            parent::response(200, $obj->getQuery2($iDisplayStart,$iDisplayLength));
        } catch (Exception $exc) {
            $status = 200;
            parent::response($status, array('msj' => $exc->getMessage()));
        }
    }
    
    public function saveAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        try {
            if(!$this->_request->isPost()) throw new Exception('Request bad!'); 
            $params = $this->_getAllParams();
            $data = $this->_paramsSave($params);
            $obj = new Application_Entity_RunSql(self::_TABLECLASS);            
            if(empty($data['id'])){
                $obj->save = $data;
                $id = $obj->save;
                $msg = 'Se guardo correctamente';
            }else{
                $obj->edit = $data;
                $msg = 'Se edito correctamente';
            }
            $rpta = array('state'=>1,'msg'=>$msg);
        } catch (Exception $exc) {
            $rpta = array('state'=>0,'msg'=>$exc->getMessage());
        }
        parent::response(200, $rpta);
    }
    
    public function _paramsSave($params)
    {
        if(empty($params['name']) || empty($params['idubigeo']) || empty($params['idcompany']) || empty($params['picture'])){
            throw new Exception('Wrong Params');
        }
        
        return array(
            'name' => $params['name'],
            'idubigeo' => $params['idubigeo'],
            'idcompany' => $params['idcompany'],
            'id' => isset($params['id']) && !empty($params['id'])?$params['id']:null,
            'picture' => isset($params['picture']) && !empty($params['picture'])?$params['picture']:null,
        );
    }
    
    public function imageAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $paramFolder = $this->_getParam('folder', null);
        try {
            $rpta = parent::uploadImage($paramFolder);
        } catch (Exception $ex) {
            $rpta = array('state' => 0,'msj' => $ex->getMessage());
        }
        parent::response(200,$rpta);
    }
    
    public function deleteAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $id = $this->_getParam('id', 0);
        try {
            if(empty($id)) throw new Exception('wrong params');
            $obj = new Application_Entity_RunSql(self::_TABLECLASS);
            $obj->edit = array($obj->getPK()=>$id, 'flagactive'=>0);
            $rpta = array('state' => 1, 'msg' => 'Item eliminado');
        } catch (Exception $exc) {
            $rpta = array('state' => 0,'msj' => $exc->getMessage());
        }
        parent::response(200,$rpta);
    }

    public function tableIcons(){
        return '<a href="javascript:;" data-id="__ID__" data-target="#tplAds" title="Editar" class="padding-right-small lnkEdit"><i class="fa fa-pencil"></i></a><a class="lnkDel" data-id="__ID__" title="Eliminar" href="javascript:;"><i class="fa fa-trash-o"></i></a>';
    }
}