<?php

namespace model;

use lib\DB;
use lib\App;

class UsersModel extends DB{

    public function __construct(){

      parent::__construct();

    }

    /**
     *@access public
     *@return array|object Success|Fail fetching data from database
     */
    public function findAll(){

      try{

        $stmt = parent::$conn->prepare("SELECT u.*, g.gender_title AS user_gender 
                            FROM users AS u
                            LEFT JOIN gender AS g
                            ON g.gender_id = u.user_gender");
        $stmt->execute();

        while($row = $stmt->fetchObject()){

          $array[] = $row;

      }

      return isset($array) ? $array : null;

    }catch(\Exception $e){
          
        echo App::_response(array("result"=>"error", "response"=>"Internal Server Error"), 500);
        exit();
        
      }

    }
    /**
     *@access public
     *@param int $id User id
     *@return array|object Success|Fail fetching data from database
     */
    public  function find(int $userid){

      try{

        $stmt = parent::$conn->prepare("SELECT u.*, g.gender_title AS user_gender 
                                FROM users AS u
                                LEFT JOIN gender AS g
                                ON g.gender_id = u.user_gender
                                WHERE u.user_id = :id");
        $stmt->bindValue(":id", $userid, \PDO::PARAM_INT);
        $stmt->execute();


        $array = array();

        return $stmt->fetchObject();

      }catch(\Exception $e){
        
        echo App::_response(array("result"=>"error", "response"=>"Internal Server Error"), 500);
        exit();
        
      }      
    }

    /**
     *@access public
     *@param int $userid User id
     *@return boolean|object Success|Fail deleting data from database
     */
    public function delete(int $userid){

        parent::$conn->beginTransaction();

        $stmt = parent::$conn->prepare("DELETE FROM users WHERE user_id = :id");
        $stmt->bindValue(":id", $userid, \PDO::PARAM_INT);
        $stmt->execute();

        try{

          if($stmt->rowCount() > 0){
            
            parent::$conn->commit();

            return true;

          }else{

            parent::$conn->rollback();

            return false;

          }

        }catch(\Exception $e){
          
          echo App::_response(array("result"=>"error", "response"=>"Internal Server Error"), 500);
          exit();
          
        }

    }
    
    /**
     *@access public
     *@return boolean|object Success|Fail updating data into database
     */
    public function update(){

      parent::$conn->beginTransaction();

      $stmt = parent::$conn->prepare("UPDATE users SET user_name = :name, 
      user_email = :email, 
      user_gender = :gender, 
      user_birthdate = :birthdate 
      WHERE user_id = :id");

      $stmt->bindValue(":name", $this->_getName());
      $stmt->bindValue(":email", $this->_getEmail());
      $stmt->bindValue(":gender", $this->_getGender());
      $stmt->bindValue(":birthdate", $this->_getBirthDate());
      $stmt->bindValue(":id", $this->_getId());
      
      try{
          
        if($stmt->execute()){

          parent::$conn->commit();
          return true;

        }else{

          parent::$conn->rollback();
          return false;

        }

      }catch(\Exception $e){
          
        echo App::_response(array("result"=>"error", "response"=>"Internal Server Error"), 500);
        exit();
        
      }  

    }
    /**
     *@access public
     *@return boolean Success|Fail inserting data into database
     */
    public function create(){

      parent::$conn->beginTransaction();

      $stmt = parent::$conn->prepare("INSERT INTO users (`user_name`, `user_email`, `user_gender`, `user_birthdate`) 
      VALUES(:name, :email, :gender, :birthdate)");

      $stmt->bindValue(":name", $this->_getName());
      $stmt->bindValue(":email", $this->_getEmail());
      $stmt->bindValue(":gender", $this->_getGender());
      $stmt->bindValue(":birthdate", $this->_getBirthDate());
      
      try{

        if($stmt->execute()){

          $id = parent::$conn->lastInsertId();
          parent::$conn->commit();
          
          $this->_setId($id);

          return true;

        }else{

          parent::$conn->rollback();

          return false;
          
        }
        
      }catch(\Exception $e){
        
        echo App::_response(array("result"=>"error", "response"=>"Internal Server Error"), 500);
        exit();
        
      }

    }

    public function emailExists(){

      try{

        $stmt = parent::$conn->prepare("SELECT user_id FROM users
                                WHERE user_email = :email");
        $stmt->bindValue(":email", $this->_getEmail(), \PDO::PARAM_STR);
        $stmt->execute();

        if($stmt->rowCount() > 0){

          return true;

        }else{

          return false;

        }

      }catch(\Exception $e){
        
        echo App::_response(array("result"=>"error", "response"=>"Internal Server Error"), 500);
        exit();
        
      }     

    }

}