<?php

namespace model;

use lib\Model;

class UsersModel extends Model{

    public function findAll(){

        return $this->Query("SELECT u.*, g.gender_title AS user_gender 
                            FROM users AS u
                            LEFT JOIN gender AS g
                            ON g.gender_id = u.user_gender");

    }

    public  function find(int $userid){

      //  return $this->Select()

    }
    
}