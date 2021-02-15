<?php 

namespace lib;


use lib\Bootstrap;

class App{

    private $endpoint;
    private $params;
    private $controller;
    private $action;
    private $method;

    public function __construct(){

        $this->_setEndPoint();
        $this->_setController();
        $this->_setAction();
        $this->_setParams();
    }


    private final function _setEndPoint(){
       
        $this->endpoint = !empty($_REQUEST['endpoint']) ? explode('/', $_REQUEST['endpoint']) : null;

    }


    private function _setController(){

        $this->controller = !empty($this->endpoint[0]) ? 'controller\\'.ucfirst($this->endpoint[0]) : null;
        $this->endpoint ? array_shift($this->endpoint) : null;
    }


    private function _setAction(){

        $this->action = !empty($this->endpoint[0]) ? $this->endpoint[0] : null;
        $this->endpoint ? array_shift($this->endpoint) : null;

    }


    private function _setParams(){

        $this->params = !empty($this->endpoint[0]) ? $this->endpoint[0] : null;

    }


    private final function _validateController(){

        if(!class_exists($this->controller)){
            self::_response(array("status"=>"error", "response"=>"Invalid endpoint"), 404);
            exit;
        }
    }


    public function _validateAction(){

        if(!method_exists($this->controller, $this->action)){

            self::_response(array("status"=>"error", "response"=>"Invalid endpoint"), 404);
            exit;           

        }
    }

    /**
     * @param string $method Valid HTTP Method for request
     * @return boolean
     */

    public final function _validateMethod(string $method){

        if($_SERVER['REQUEST_METHOD'] != $method){

            self::_response(array("status"=>"error", "response"=>"Invalid method"), 405);
            exit;

        }else{

            return true;
        }

    }



    private function _open(){

        try{
            
            call_user_func_array(array(new $this->controller, $this->action), array($this->params));

        }catch(Exception $e){

            exit($e->getMessage());

        }


    }
    /** 
     * Prepare response for request sent    
    * @param array $data
    * @param int $status
    * @return void
    **/
    public static function _response(array $data, int $status = 200) {

        header("HTTP/1.1 " . $status . " " . self::_responseStatus($status));
        echo json_encode($data, JSON_UNESCAPED_UNICODE);

    }

    /**
     * HTTP status based on code
     * @param int $code 
     * @return string 
     */

    private final function _responseStatus(int $code) {
        
        $status = array(  
            200 => 'OK',
            400 => 'Bad Request',
            404 => 'Not Found',   
            405 => 'Method Not Allowed',
            503 => 'Access Denied',
            500 => 'Internal Server Error',
        ); 

        return ($status[$code]) ? $status[$code] : $status[500]; 
    }



    /**
     * @return void 
     */

    public function run(){

        $this->_validateController();
        $this->_validateAction();
        $this->_open();
    }

}




