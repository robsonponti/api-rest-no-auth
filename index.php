<?php 

ob_start();
header("Access-Control-Allow-Orgin: *");
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    define('SERVICES_PATH', 'services');    

    @date_default_timezone_set("Europe/Lisbon");

    /** Auto loading classes
     *@param string $class class path
     */

    spl_autoload_register(function($class){

        $file = str_replace('\\', '/', $class) . '.php';

        if(file_exists($file)){
            
            require_once $file;


                if(!in_array($class, get_declared_classes())){
                
                    new $class;
                
                }

            }

    });

    $app = new lib\App();
    $app->run();
?>