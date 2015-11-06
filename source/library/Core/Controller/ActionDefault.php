<?php

class Core_Controller_ActionDefault extends Core_Controller_Action {

    protected $_identity;
    public function init() {
        parent::init();
        $this->_identity =  Zend_Auth::getInstance()->getIdentity();
        $this->permisos();
        $this->view->identity = $this->_identity;
    }
    
    function permisos() {
        $auth = Zend_Auth::getInstance();
        $controller=$this->_request->getControllerName();
        $action=$this->_request->getActionName();
        if(!$auth->hasIdentity()) {
            if ($controller!='login' && $controller!='service' && $controller!='register') {
                $this->_redirect('/login');
            }
        }else{
            if ($controller=='login' && $action == 'index') {
                $this->_redirect('/');
            }
        }
    }
    
    public function preDispatch() {
        parent::preDispatch();       
        $this->view->menu=$this->getMenu();  
        $this->view->controller = $this->getRequest()->getControllerName();
        $this->view->action = $this->getRequest()->getActionName();
        $this->view->identity = $this->_identity;                
        $this->view->dataYOSON = array('controller' => $this->getRequest()->getControllerName(),
            'action' => $this->getRequest()->getActionName(), 'module' => $this->getRequest()->getModuleName());
    }
    
    
    public function auth($usuario,$password)
    {                            
        $dbAdapter = Zend_Db_Table_Abstract::getDefaultAdapter();
        $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
        $authAdapter
            ->setTableName('cn_admin')
            ->setIdentityColumn('email')
            ->setCredentialColumn('password')
            ->setIdentity($usuario)
            ->setCredential(md5($password));
        $select = $authAdapter->getDbSelect();
        $select->where('flagactive = 1');
        $result = Zend_Auth::getInstance()->authenticate($authAdapter);
        if ($result->isValid()){
            $storage = Zend_Auth::getInstance()->getStorage();
            $bddResultRow = $authAdapter->getResultRowObject();
            $storage->write($bddResultRow);
            $msj = 'Bienvenido Usuario '.$result->getIdentity();
            $this->view->message=$this->_flashMessenger->success($msj);            
            $this->_identity = Zend_Auth::getInstance()->getIdentity();              
            $return = true;            
        } else {                
            switch ($result->getCode()) {
                case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND:
                    $msj = 'El usuario no existe';
                    break;
                case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
                    $msj = 'Password incorrecto';
                    break;
                default:
                    $msj='Datos incorrectos';
                    break;
            }             
           $this->view->message=$this->_flashMessenger->warning($msj);          
            $return = false;
        }  
        return $return;
    }
    
    function getMenu()
    {
        $menu = array(
            'profile'=>
            array('class'=>'icMPerfil','url'=>'/profile/','title'=>'Perfil'),       
        );
        return $menu;
    }
    
    protected function response($code, array $rpta, $contentType = 'application/json;charset=UTF-8') {
        $this->getResponse()->setHttpResponseCode($code)
                ->setHeader('Content-type', $contentType, true)
                ->appendBody(json_encode($rpta));
    }
   
    public function uploadImage($paramFolder)
    {
        $folder = array('admin', 'company', 'ads', 'movie');
        if(in_array($paramFolder, $folder)){
            $path = $paramFolder.'/';
            if(!is_dir(ROOT_IMG_DINAMIC. '/' . $path )){
                mkdir(ROOT_IMG_DINAMIC. '/' . $path , 0777, true);
            }
            $upload = new Zend_File_Transfer_Adapter_Http();
            $nameFile = $upload->getFileName('inputFile', false);
            $pathFolder = ROOT_IMG_DINAMIC . '/'.$paramFolder;
            $upload->setDestination($pathFolder);
            $newImgName = uniqid() . '_' . $nameFile;
            $name = $pathFolder . '/' . $newImgName;
            $upload->addFilter('Rename', array('target' => $name));
            $upload->receive();
            $resize = 'resizeImg'.ucfirst ($paramFolder);
            if(method_exists(get_class($this), $resize)){
                $this->$resize($name, $paramFolder);
            }      
            $rpta=  array(
                  'state' => 1,
                  'msg' => 'ok',
                  'img' => $paramFolder.'/'.$newImgName,
                  'nameImg' => $newImgName,
              );
            return $rpta;
        }else
            throw new Exception('La carpeta no existe');          
    }
    
    public function _btnList($page)
    {
        return '<a href="/'.$page.'/detail/id/__ID__" title="Editar" class="padding-right-small"><i class="fa fa-pencil"></i></a><a class="lnkDel" data-id="__ID__" title="Eliminar" href="javascript:;"><i class="fa fa-trash-o"></i></a>';
    }
}