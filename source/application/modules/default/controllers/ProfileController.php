<?php


class Default_ProfileController extends Core_Controller_ActionDefault
{
    public function init() {
        parent::init();
    }
    
    public function preDispatch() {
        parent::preDispatch();
    }
    
    public function indexAction()
    {
        $id = $this->_identity->id;
        $form = new Default_Form_Admin();
        $obj = new Application_Entity_RunSql('Admin');
        $obj->getone = array($id);
        $data = $obj->getone;
        $data['admin'] = $data['id'];
        $form->populate($data);
        $this->view->form = $form;
    }
    
    public function saveAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        try {
            if(!$this->_request->isPost()) throw new Exception('Request bad!'); 
            $params = $this->_getAllParams();
            $data = $this->_paramsSave($params);
            $obj = new Application_Entity_RunSql('Admin');
            
            $form = new Default_Form_Admin();
            if(!$form->isValid($data)) throw new Exception('Ingrese correctamente los campos del formulario');
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
        if(empty($params['name']) || empty($params['phone'])
                || empty($params['lastnamepaternal']) || empty($params['lastnamematernal'])){
            throw new Exception('Wrong Params');
        }
        
        return array(
            'name' => $params['name'],
            'lastnamepaternal' => $params['lastnamepaternal'],
            'lastnamematernal' => $params['lastnamematernal'],
            'password' => isset($params['password']) && !empty($params['password'])?$params['password']:null,
            'phone' => $params['phone'],
            'id' => $this->_identity->id,
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

}