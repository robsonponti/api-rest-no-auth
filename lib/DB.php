<?php

namespace lib;

class DB{

    private static $db_host = "localhost";
    private static $db_username = "root";
    private static $db_password = "rob080594";
    private static $db_name = "oskon_api";
    protected static $conn;

    public function __construct(){

        try{

            self::$conn = new \PDO("mysql:host=".self::$db_host."; 
            dbname=".self::$db_name, 
            self::$db_username, self::$db_password,
            array(\PDO::MYSQL_ATTR_INIT_COMMAND=>"set names utf8"));
            self::$conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            self::$conn->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);


        }catch(Exception $e){
            
            exit($e->getMessage());
        }

        return self::$conn;
        
    }




}