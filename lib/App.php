<?php 

namespace lib;


use lib\Bootstrap;

class App{

    private $endpoint;
    private $params;
    private $controller;
    private $action;
    private $method;

    /**
     *@access public
     *@return void
     */
    public function __construct(){

        $this->_setEndPoint();
        $this->_setController();
        $this->_setAction();
        $this->_setParams();
    }

    /**
     *@access private
     *@return void
     */
    private final function _setEndPoint(){
       
        $this->endpoint = !empty($_REQUEST['endpoint']) ? explode('/', $_REQUEST['endpoint']) : null;

    }

    /**
     *@access private
     *@return void
     */
    private function _setController(){

        $this->controller = !empty($this->endpoint[0]) ? 'controller\\'.ucfirst($this->endpoint[0]) : null;
        $this->endpoint ? array_shift($this->endpoint) : null;
    }

    /**
     *@access private
     *@return void
     */
    private function _setAction(){

        $this->action = !empty($this->endpoint[0]) ? $this->endpoint[0] : null;
        $this->endpoint ? array_shift($this->endpoint) : null;

    }

    /**
     *@access private
     *@return void
     */
    private function _setParams(){

        $this->params = !empty($this->endpoint[0]) ? $this->endpoint[0] : null;

    }

    /**
     *@access private
     *@return object Fail message
     */
    private final function _validateController(){

        if(!class_exists($this->controller)){
            self::_response(array("status"=>"error", "response"=>"Invalid endpoint"), 404);
            exit;
        }
    }

    /**
     *@access private
     *@return object Fail message
     */
    private function _validateAction(){

        if(!method_exists($this->controller, $this->action)){

            self::_response(array("status"=>"error", "response"=>"Invalid endpoint"), 404);
            exit;           

        }
    }

    /**
     * @access public
     * @param string $method HTTP Method used in request
     * @return object|boolean Success|Fail 
     */
    public final function _validateMethod(string $method){

        if($_SERVER['REQUEST_METHOD'] != $method){

            self::_response(array("status"=>"error", "response"=>"Invalid method"), 405);
            exit;

        }else{

            return true;
        }

    }



    /**
     * @access private
     * @return void|string Success|Exception Message
     */
    private function _open(){

        try{
            
            call_user_func_array(array(new $this->controller, $this->action), array($this->params));

        }catch(Exception $e){

            exit($e->getMessage());

        }


    }

    /** 
    *@access public
    * @param array $data Response Data to return in response body
    * @param int $status HTTP Status to return in response body
    * @return object Response body
    **/
    public static function _response(array $data, int $status = 200) {

        header("HTTP/1.1 " . $status . " " . self::_responseStatus($status));
        echo json_encode($data, JSON_UNESCAPED_UNICODE);

    }

    /**
     *@access private
     *@param int $code HTTP Status code to return in response body
     *@return string 
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
    *@access public
    *@return void 
    */
    public function run(){

        $this->_validateController();
        $this->_validateAction();
        $this->_open();
    }

}




