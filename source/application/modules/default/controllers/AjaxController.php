<?php


class Default_AjaxController extends Core_Controller_ActionDefault
{
    public function init() {
        parent::init();
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
    }
    
    public function preDispatch() {
        parent::preDispatch();
    }
    
    public function indexAction()
    {
        
    }
    
    public function getCompanyAction()
    {
        $id = $this->_getParam('id', 0);
        try {
            if(empty($id)) throw new Exception('wrong params');
            $data = Default_Model_Company::getRow($id);           
            $rpta = array('state' => 1, 'msg' => 'ok', 'data' => $data);
        } catch (Exception $exc) {
            $rpta = array('state' => 0,'msj' => $exc->getMessage());
        }
        parent::response(200,$rpta);
    }
    
    public function getSubsidiaryAction()
    {
        $id = $this->_getParam('id', 0);
        try {
            if(empty($id)) throw new Exception('wrong params');
            $data = Default_Model_Subsidiary::getRow($id);
            $rpta = array('state' => 1, 'msg' => 'ok', 'data' => $data);
        } catch (Exception $exc) {
            $rpta = array('state' => 0,'msj' => $exc->getMessage());
        }
        parent::response(200,$rpta);
    }
    
    public function getGenreAction()
    {
        $id = $this->_getParam('id', 0);
        try {
            if(empty($id)) throw new Exception('wrong params');
            $data = Default_Model_Genre::getRow($id);
            $rpta = array('state' => 1, 'msg' => 'ok', 'data' => $data);
        } catch (Exception $exc) {
            $rpta = array('state' => 0,'msj' => $exc->getMessage());
        }
        parent::response(200,$rpta);
    }
    
    public function getAdsAction()
    {
        $id = $this->_getParam('id', 0);
        try {
            if(empty($id)) throw new Exception('wrong params');
            $data = Default_Model_Ads::getRow($id);
            $rpta = array('state' => 1, 'msg' => 'ok', 'data' => $data);
        } catch (Exception $exc) {
            $rpta = array('state' => 0,'msj' => $exc->getMessage());
        }
        parent::response(200,$rpta);
    }
    
    public function getMovieAction()
    {
        $id = $this->_getParam('id', 0);
        try {
            if(empty($id)) throw new Exception('wrong params');
            $data = Default_Model_Movie::getRow($id, true);
            $rpta = array('state' => 1, 'msg' => 'ok', 'data' => $data);
        } catch (Exception $exc) {
            $rpta = array('state' => 0,'msj' => $exc->getMessage());
        }
        parent::response(200,$rpta);
    }
    
    public function getBillboardAction()
    {
        $id = $this->_getParam('id', 0);
        try {
            if(empty($id)) throw new Exception('wrong params');
            $data = Default_Model_Billboard::getRow($id, true);
            $rpta = array('state' => 1, 'msg' => 'ok', 'data' => $data);
        } catch (Exception $exc) {
            $rpta = array('state' => 0,'msj' => $exc->getMessage());
        }
        parent::response(200,$rpta);
    }
    
    public function getPriceAction()
    {
        $id = $this->_getParam('id', 0);
        try {
            if(empty($id)) throw new Exception('wrong params');
            $data = Default_Model_Price::getRow($id, true);
            $rpta = array('state' => 1, 'msg' => 'ok', 'data' => $data);
        } catch (Exception $exc) {
            $rpta = array('state' => 0,'msj' => $exc->getMessage());
        }
        parent::response(200,$rpta);
    }
}