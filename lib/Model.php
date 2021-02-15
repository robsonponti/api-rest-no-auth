<?php

namespace lib;

use lib\DB;

class Model extends DB{

    
    public function __construct(){
  
        parent::__construct();

    }

    /**
     * @param array $obj SQL Cols and Values
     * @param string $table MySQL Tablename
     * @return int last inserted id
     */
    public final function Insert(array $obj, string $table){

        try{
           
            $query = "INSERT INTO {$table} (".implode(",",array_keys((array) $obj)).") VALUES 
            ('".implode("','", array_values((array) $obj))."')";

            $stmt = parent::$conn->prepare($query);
            $stmt->execute();
        
        }catch(\PDOException $e){

           exit($e->getMessage() ." " . $query);

        }

        return parent::$conn->lastId() ? parent::$conn->lastId() : 0;
        
    }


    /** 
    * @param string $query MySQL Query
    * @return array
    **/
    public final function Query(string $query){

        try{
            $stmt = parent::$conn->prepare($query);
            $stmt->execute();
        }

        catch(\PDOException $e){
           
           exit($e->getMessage());

        }


        while($row = $stmt->fetchObject()){

            $array[] = $row;

        }

        return isset($array) ? $array : null;

    }


    /**
     * @param array $condidition Clausule Where
     * @param string $table MySQL Tablename
     * @return boolean
     */
    public final function Delete(array $condidition, string $table){

        try{

            foreach ($condition as $index => $val){
                
                $where[] = "{$index}". (is_null($val) ? " IS NULL " : " = '{$val}'");
            
            }

            $sql = "DELETE FROM {$table} WHERE ". implode(' AND ', $where);
            $state = parent::$conn->prepare($sql);
            $state->execute();
           
            if($state->rowCount() > 0){

                return true;

            }else{

                return false;

            }

        }

        catch(\PDOException $e){

           exit($e->getMessage());

        }


    }


    /**
     * @param array $obj Columns and Values
     * @param array $condition Where Clausue
     * @param string $table MySQL Tablename
     * @return boolean
     */
    public final function Update($obj, $condition, $table){

        try {

            foreach($obj as $ind => $val){
             $dados[] = "{$ind} = ".(is_null($val) ? " NULL " : "'{$val}'");   
            }

            foreach($condition as $ind => $val){
             $where[] = "{$ind}".(is_null($val) ? " IS NULL " : " = '{$val}'");   
            }

            $sql = "UPDATE {$table} SET ". implode(',', $dados)." WHERE ". implode(' AND ', $where);
            
            $state = parent::$conn->prepare($sql);
            $state->execute();
            
            return true;


        } catch (\PDOException $ex){
            
            exit ($ex->getMessage(). " ". $sql);

        }

        return false;
    }


}


