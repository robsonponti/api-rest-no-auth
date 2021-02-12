<?php 

namespace services;

use lib\App;
use model\UsersModel;

class Users extends UsersModel{


    /** Get User by id
    *@param int $userid Integer
    *@return object
    */
    public function get(int $userid){


        
    }

    /** Get All Users
    *@return array
    */
    public function getAll(){

        foreach($this->findAll() as $kUser => $user){

            $users[] = array(
                'name'=>$user->user_name, 
                'email'=>$user->user_email,
                'gender'=>$user->user_gender,
                'birthday'=>$user->user_birthdate
            );

        }
        if(isset($users)){
        
            App::_response($users, 200);
        
        }else{

            App::_response(array("result"=>"succes", "response"=>"Sorry, there is no users to show."), 200);
        }
        
    }


    public function create(){




    }

}




