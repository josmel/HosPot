<?php

class Service_UpdatesController extends REST_Controller {

    public function init() {
        parent::init();
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
    }

    public function preDispatch() {
        parent::preDispatch();
    }

    public function indexAction() {
        
    }

    public function deleteAction() {
        echo 'delete';
    }

    public function getAction() {
        try {
            $lastUpdate = $this->_getParam('lastupdate', date('Y-m-d H:i:s'));

//            $writer = new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../var/log/logUpdates.log');
//            $log = new Zend_Log($writer);
//            $log->info("---------  INICIO GET UPDATE --------------");
//            $log->info("params : $lastUpdate" . PHP_EOL);
//            $log->info("------------ FIN GET UPDATE --------------");

            $fields = array('id', 'name', 'picture', 'picture', 'picture', 'paidads', 'flagactive');
            $objCompany = new Application_Entity_RunSql('Company', "lastupdate > '" . urldecode($lastUpdate) . "' AND flagactive=1", null, $fields);
            $objCompany->listed = array();
            $company = $objCompany->listed;
            $fields = array('id', 'idubigeo', 'idcompany', 'name', 'address', 'flagactive');
            $objSubsidiary = new Application_Entity_RunSql('Subsidiary', "lastupdate > '" . urldecode($lastUpdate) . "' AND flagactive=1", null, $fields);
            $objSubsidiary->listed = array();
            $subsidiary = $objSubsidiary->listed;
            $fields = array('id', 'idmovie', 'idsubsidiary', 'schedule3dsubtitle', 'schedule3ddubbing', 'schedulesubtitle', 'scheduledubbing', 'flagactive');
            $objBillboard = new Application_Entity_RunSql('Billboard', "lastupdate > '" . urldecode($lastUpdate) . "' AND flagactive=1", null, $fields);
            $objBillboard->listed = array();
            $billboard = $objBillboard->listed;
            $fields = array('id', 'name', 'idday', 'value', 'idsubsidiary', 'flagactive');
            $objPrice = new Application_Entity_RunSql('Price', "lastupdate > '" . urldecode($lastUpdate) . "' AND flagactive=1", null, $fields);
            $objPrice->listed = array();
            $price = $objPrice->listed;
            $fields = array('id', 'idgenre', 'idubigeo', 'name', 'datepublication', 'premiere', 'synopsis', 'director', 'cast', 'urltrailer', 'picture', 'duration', 'imdb', 'censure', 'flagactive');
            $objMovie = new Application_Entity_RunSql('Movie', "lastupdate > '" . urldecode($lastUpdate) . "' AND flagactive=1", null, $fields);
            $objMovie->listed = array();
            $movie = $objMovie->listed;
//            $fields = array('id', 'name', 'flagactive');
//            $objState = new Application_Entity_RunSql('Ubigeo', "lastupdate > '" . urldecode($lastUpdate) . "' AND flagactive=1 AND idcountry = 1 
//                AND idstate != 0 AND idprovince = 0 AND iddistrict = 0 ", null, $fields);
//            $objState->listed = array();
//            $state = $objState->listed;
//            $fields = array('id', 'name', 'flagactive');
//            $objCountry = new Application_Entity_RunSql('Ubigeo', "lastupdate > '" . urldecode($lastUpdate) . "' AND flagactive=1 
//                AND idstate = 0 AND idprovince = 0 AND iddistrict = 0 ", null, $fields);
//            $objCountry->listed = array();
//            $country = $objCountry->listed;
            $fields = array('id', 'idubigeo', 'idcompany', 'name', 'picture', 'flagactive');
            $objAds = new Application_Entity_RunSql('Ads', "lastupdate > '" . urldecode($lastUpdate) . "' AND flagactive=1", null, $fields);
            $objAds->listed = array();
            $ads = $objAds->listed;
            $fields = array('id', 'name', 'flagactive');
            $objGenre = new Application_Entity_RunSql('Genre', "lastupdate > '" . urldecode($lastUpdate) . "' AND flagactive=1", null, $fields);
            $objGenre->listed = array();
            $genre = $objGenre->listed;

            $rpta = array(
                'state' => 1,
                'msg' => 'ok',
                'data' => array(
                    'company' => $company,
                    'subsidiary' => $subsidiary,
                    'billboard' => $billboard,
                    'price' => $price,
                    'movie' => $movie,
//                    'state' => $state,
//                    'country' => $country,
                    'ads' => $ads,
                    'genre' => $genre,
                ),
            );
        } catch (Exception $exc) {
            $rpta = array(
                'state' => 0,
                'msg' => $exc->getMessage(),
            );
        }
        parent::response(200, $rpta);
    }

    public function headAction() {
        echo 'head';
    }

    public function postAction() {
        echo 'post';
    }

    public function putAction() {
        echo 'put';
    }

}
