<?php
/**
 * REST Controller default actions
 *
 */
abstract class REST_Controller extends Zend_Controller_Action
{
    /**
     * The index action handles index/list requests; it should respond with a
     * list of the requested resources.
     */
    public function indexAction()
    {
        $this->notAllowed();
    }

    /**
     * The get action handles GET requests and receives an 'id' parameter; it
     * should respond with the server resource state of the resource identified
     * by the 'id' value.
     */
    public function getAction()
    {
        $this->notAllowed();
    }

    /**
     * The post action handles POST requests; it should accept and digest a
     * POSTed resource representation and persist the resource state.
     */
    public function postAction()
    {
        $this->notAllowed();
    }

    /**
     * The put action handles PUT requests and receives an 'id' parameter; it
     * should update the server resource state of the resource identified by
     * the 'id' value.
     */
    public function putAction()
    {
        $this->notAllowed();
    }

    /**
     * The delete action handles DELETE requests and receives an 'id'
     * parameter; it should update the server resource state of the resource
     * identified by the 'id' value.
     */
    public function deleteAction()
    {
        $this->notAllowed();
    }

    /**
     * The head action handles HEAD requests; it should respond with an
     * identical response to the one that would correspond to a GET request,
     * but without the response body.
     */
    public function headAction()
    {
        $this->_forward('get');
    }

    /**
     * The options action handles OPTIONS requests; it should respond with
     * the HTTP methods that the server supports for specified URL.
     */
    public function optionsAction()
    {
        $this->_response->setBody(null);
        $this->_response->setHeader('Allow', $this->_response->getHeaderValue('Access-Control-Allow-Methods'));
        $this->_response->ok();
    }

    protected function notAllowed()
    {
        $this->_response->setBody(null);
        $this->_response->notAllowed();
    }
    
    /**
     * Ouput Header and content JSOn
     */
    
    protected function response($code, array $rpta, $contentType = 'application/json;charset=UTF-8') {
        $this->getResponse()->setHttpResponseCode($code)
                ->setHeader('Content-type', $contentType, true)
                ->appendBody(json_encode($rpta));
    }
    
    protected function _validateParam($params, $search, $returnArray = false, $typeParam = 'OBJ')
    {
        $return = false;
        $data = array();
        foreach ($search as $value) {
            $val = $params->$value;
            if(strtoupper($typeParam) != 'OBJ') $val = $params[$value];
            if(isset($val) && ($val)!==''){
                if($returnArray){
                    $data[$value] = $val;
                    $return = $data;
                }else{
                    $return = true;
                }
            }else{
                $return = false;
                break;
            }
        }
        return $return;
    }
    
    protected function _parseToken($dataPush,$message,$val = 'token')
    {
        $data=array();
        if(is_array($dataPush) && !empty($dataPush)){
            $data=array('message'=>$message,'device'=>array());
            foreach($dataPush AS $value){
                $data['device'][]=$value[$val];
            }
        }
        return $data;
    }
}
