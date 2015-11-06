<?php


class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    public function _initView()
    {                
        
        $this->bootstrap('layout');        
        $layout = $this->getResource('layout');
        $v = $layout->getView();
        $v->addHelperPath('Core/View/Helper', 'Core_View_Helper');
       
        
       
    }
    
    public function _initResource()
    {
//        $front     = Zend_Controller_Front::getInstance();         
//        $restRoute = new Zend_Rest_Route($front);
//        $front->getRouter()->addRoute('service', $restRoute);  
        
         //Definiendo Constante para Partials     
        $config = Zend_Registry::get('config');        
        $this->getResourceLoader()->addResourceType('entity', 'entitys/', 'Entity');
        defined('STATIC_URL')
            || define('STATIC_URL', $config['app']['staticUrl']);
        defined('DINAMIC_URL')
            || define('DINAMIC_URL', $config['app']['dinamicUrl']);
        defined('SITE_URL')
            || define('SITE_URL', $config['app']['siteUrl']);
        defined('ROOT_IMG_DINAMIC')
            || define('ROOT_IMG_DINAMIC',$config['app']['rootImgDinamic']);
        defined('COMPANY')
            || define('COMPANY',1);
    }

  
    
    public function _initRegistries()
    {        
        $this->bootstrap('multidb');
        $db = $this->getPluginResource('multidb')->getDb('db');
        Zend_Db_Table::setDefaultAdapter($db);
        //$multidb = $this->getPluginResource('multidb');
        Zend_Registry::set('multidb', $db);
            if ('local' == APPLICATION_ENV) {
            $profiler = new Zend_Db_Profiler_Firebug('All DB Queries');
            $profiler->setEnabled(true);      
            $db->setProfiler($profiler);
        }
        Zend_Registry::set('mail',$this->getOption('mail'));  
        Zend_Registry::set('push',$this->getOption('push'));  
        $this->bootstrap('cachemanager');
         $cache = $this->getResource('cachemanager')->getCache('file');
         Zend_Registry::set('cache', $cache); 
//        $this->_executeResource('log');
//        $log = $this->getResource('log');
//        Zend_Registry::set('log', $log);
    }

    public function _initActionHelpers()
    {
//        Zend_Controller_Action_HelperBroker::addHelper(
//            new Core_Controller_Action_Helper_Auth()
//        );
//        Zend_Controller_Action_HelperBroker::addHelper(
//            new App_Controller_Action_Helper_Security()
//        );
        /*Zend_Controller_Action_HelperBroker::addHelper(
            new Core_Controller_Action_Helper_Mail()
        );*/
        
    }

    public function _initREST() {
        $frontController = Zend_Controller_Front::getInstance();

        // set custom request object
        $frontController->setRequest(new REST_Request);
        $frontController->setResponse(new REST_Response);

        // add the REST route for the API module only
        $restRoute = new Zend_Rest_Route($frontController, array(), array('service'));
        $frontController->getRouter()->addRoute('rest', $restRoute);
    }
    
    
//    public function _initTranslate()
//    {
//        $translator = new Zend_Translate(
//                Zend_Translate::AN_ARRAY,
//                APPLICATION_PATH . '/configs/languages/',
//                'es',
//                array('scan' => Zend_Translate::LOCALE_DIRECTORY)
//        );
//        
//        Zend_Validate_Abstract::setDefaultTranslator($translator);        
//    }
      protected function _initAutoload() {
          
          
        new Zend_Application_Module_Autoloader(array(
            'namespace' => 'Application',
            'basePath' => APPLICATION_PATH . '/models',
        ));
        new Zend_Application_Module_Autoloader(array(
            'namespace' => 'Default',
            'basePath' => APPLICATION_PATH . '/entitys/default',
        ));
        new Zend_Application_Module_Autoloader(array(
            'namespace' => 'Notification',
            'basePath' => APPLICATION_PATH . '/entitys/notification',
        ));
        new Zend_Application_Module_Autoloader(array(
            'namespace' => 'Package',
            'basePath' => APPLICATION_PATH . '/entitys/package',
        ));           
        
    }

}

