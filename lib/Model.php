<?php

namespace lib;

use lib\DB;

class Model extends DB{

    
    public function __construct(){
  
        parent::__construct();

    }

    public final function Insert(){

        
    }

    public final function Select(){

    }

    
    /** 
    *@param string $query 
    *@return array
    **/

    public final function Query(string $query){

        try{
            $stmt = parent::$conn->prepare($query);
            $stmt->execute();
        }

        catch(\PDOException $e){
           
           die($e->getMessage());

        }

        $array = array();

        while($row = $stmt->fetchObject()){

            $array[] = $row;

        }

        return $array;

    }

    public final function Delete(){

    }

    public final function Update(){

    }
}


