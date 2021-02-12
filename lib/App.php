<?php 

namespace lib;

use lib\Bootstrap;

class App{

    private $endpoint;
    private $params;
    private $service;
    private $action;
    private $method;

    public function __construct(){

        $this->_setEndPoint();
        $this->_setService();
        $this->_setAction();
        $this->_setParams();
    }


    private final function _setEndPoint(){
       
        $this->endpoint = !empty($_REQUEST['endpoint']) ? explode('/', $_REQUEST['endpoint']) : null;

    }


    private function _setService(){

        $this->service = !empty($this->endpoint[0]) ? SERVICES_PATH.'\\'.ucfirst($this->endpoint[0]) : null;
        $this->endpoint ? array_shift($this->endpoint) : null;
    }


    private function _setAction(){

        $this->action = !empty($this->endpoint[0]) ? $this->endpoint[0] : null;
        $this->endpoint ? array_shift($this->endpoint) : null;

    }


    private function _setParams(){

        $this->params = !empty($this->endpoint[0]) ? $this->endpoint[0] : array();

    }


    private final function _validateService(){

        if(!class_exists($this->service)){
            self::_response(array("status"=>"error", "response"=>"Invalid endpoint"), 404);
            exit;
        }
    }


    public function _validateAction(){

        if(!method_exists($this->service, $this->action)){

            self::_response(array("status"=>"error", "response"=>"Invalid endpoint"), 404);
            exit;           

        }
    }


    private function _open(){

        try{
            
            call_user_func_array(array(new $this->service, $this->action), array($this->params));

        }catch(Exception $e){

            exit($e->getMessage());

        }


    }
    /** 
     * Prepare response for request sent    
    *@param array $data
    *@param int $status
    **/
    public static function _response($data, int $status = 200) {

        header("HTTP/1.1 " . $status . " " . self::_responseStatus($status));
        echo json_encode($data, JSON_UNESCAPED_UNICODE);

    }

    /**
     * Return the HTTP status based on code
     * @param int $code 
     * @return string 
     */

    private final function _responseStatus(int $code) {
        
        $status = array(  
            200 => 'OK',
            404 => 'Not Found',   
            405 => 'Method Not Allowed',
            503 => 'Access Denied',
            500 => 'Internal Server Error',
        ); 

        return ($status[$code]) ? $status[$code] : $status[500]; 
    }

    public function run(){

        $this->_validateService();
        $this->_validateAction();
        $this->_open();
    }

}




