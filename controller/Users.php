<?php 

namespace controller;

use lib\App;
use model\UsersModel;

class Users extends UsersModel{

    private $id;
    private $name;
    private $email;
    private $gender;
    private $birthdate;

    public function _setId($id){

        if(is_numeric($id)){

            $this->id = $id;

        }else{

            exit();
        }
    }

    public function _setEmail(string $email){

        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            
            $this->email = $email;
        
        }
    }

    public function _setBirthDate(string $birthdate){

        $d = \DateTime::createFromFormat('Y-m-d', $birthdate);
       
        if($d && $d->format('Y-m-d') == $birthdate){

            $this->birthdate = $birthdate;
        }

    }

    public function _setName(string $name){

        if(!empty($name) && strlen($name) > 0){

            $this->name = $name;

        }
    }

    public function _setGender(int $gender){

        if(is_integer($gender) && ($gender == 1 || $gender == 2)){

            $this->gender = $gender;

        }

    }

    public function _getName(){

        return $this->name;

    }

    public function _getId(){

        return $this->id;

    }

    public function _getEmail(){

        return $this->email;

    }

    public function _getBirthDate(){

        return $this->birthdate;

    }

    public function _getGender(){

        return $this->gender;

    }


    /** Get User by id
    *@param int $userid 
    *@return void
    */
    public function get(int $userid=null){
        
        App::_validateMethod('GET');
        
        $user = !is_null($userid) && is_integer($userid) ? $this->find($userid) : null;
        
        if($user){
        
            App::_response(
                array(
                    "id"=>$user->user_id,
                    "nome"=>$user->user_name,
                    "email"=>$user->user_email,
                    "gender"=>$user->user_gender,
                    "birthdate"=>$user->user_birthdate
                ), 
            200);

        }elseif(is_null($userid)){

            App::_response(array("result"=>"error", "response"=>"Parameter id is missing or invalid"), 400);
            exit;

        }else{

            App::_response(array("result"=>"error", "response"=>"Sorry, user not found"), 200);
            exit;

        }
    }

    /** Get All Users
    *@return void
    */
    public function getAll(){
        
        App::_validateMethod("GET");

        if(!empty($this->findAll())){

            foreach($this->findAll() as $kUser => $user){

                $users[] = array(
                    "id"=>$user->user_id,
                    'name'=>$user->user_name, 
                    'email'=>$user->user_email,
                    'gender'=>$user->user_gender,
                    'birthday'=>$user->user_birthdate
                );

            }
        }

        if(isset($users)){
        
            App::_response(array("result"=>"success", "data"=>$users), 200);
            exit;

        }else{

            App::_response(array("result"=>"success", "response"=>"Sorry, there is no users to show"), 200);
            exit;

        }
        
    }


    public function create(){

        App::_validateMethod("POST");

        $json = file_get_contents("php://input");
        $post = json_decode($json);

        if((!isset($post->name) || empty($post->name))
        || (!isset($post->gender) || empty($post->gender))
        || (!isset($post->email) || empty($post->email))
        || (!isset($post->birthdate) || empty($post->birthdate))){

            App::_response(array("result"=>"error", "response"=>"Parameter missing"), 400);
            exit;

        }else{

            $this->_setName($post->name);
            $this->_setEmail($post->email);
            $this->_setGender($post->gender);
            $this->_setBirthDate($post->birthdate);
        

            $this->save();

        }        

    }



    public function delete(int $id=null){

        App::_validateMethod("DELETE");

        if(!is_integer($id) || !isset($id)){
            
            App::_response(array("result"=>"error", "response"=>"Parameter id is missing or invalid"), 400);
            exit;

        }

        if(!parent::find($id)){

            App::_response(array("result"=>"error", "response"=>"User not found"), 400);
            exit;

        }

        if(isset($id)){

            if(parent::delete($id)){

                App::_response(array("result"=>"success", "response"=>"User removed successfuly"), 200);
                exit;
            
            }else{

                App::_response(array("result"=>"error", "response"=>"Error deleting user"), 500);
                exit;

            }


        }elseif(!isset($id) || !is_integer($id)){

            App::_response(array("result"=>"error", "response"=>"Parameter id is missing or invalid"), 400);
            exit;

        }else{

            App::_response(array("result"=>"error", "response"=>"Sorry, an error ocurred"), 500);
            exit;

        }
        
    }



    public function update(){

        App::_validateMethod("PUT");
        
        $json = file_get_contents("php://input");
        $post = json_decode($json);

        if(!is_integer($post->id) 
        || (!isset($post->id) || empty($post->id))
        || (!isset($post->name) || empty($post->name))
        || (!isset($post->gender) || empty($post->gender))
        || (!isset($post->email) || empty($post->email))
        || (!isset($post->birthdate) || empty($post->birthdate))){

            App::_response(array("result"=>"error", "response"=>"Parameter missing"), 400);
            exit;

        }else{

            if(!parent::find($post->id)){

                App::_response(array("result"=>"error", "response"=>"User not found"), 400);
                exit;
    
            }

            $this->_setId($post->id);
            $this->_setName($post->name);
            $this->_setEmail($post->email);
            $this->_setGender($post->gender);
            $this->_setBirthDate($post->birthdate);
        


            $this->save();

        }

    }


    private function save(){

        if(!$this->id){
            
            if(parent::emailExists()){

                App::_response(array("result"=>"error", "response"=>"Sorry, email already exists"), 200);
                exit();
            }

            if(parent::create()){

                App::_response(array("result"=>"success", "response"=>"User created sucessfuly", "id"=>$this->id), 200);
                exit;
                
            }else{

                App::_response(array("result"=>"error", "response"=>"Sorry, an error occurred creating user"), 500);
                exit;
            }

        }else{

            if(parent::update()){

                $user = parent::find($this->id) ? parent::find($this->id) : null;
                $data = !empty($user) ? array(
                    "id"=>$user->user_id,
                    "nome"=>$user->user_name,
                    "email"=>$user->user_email,
                    "gender"=>$user->user_gender,
                    "birthdate"=>$user->user_birthdate
                ) : null; 
                
                App::_response(array("result"=>"success", "response"=>"User updated sucessfuly", "data"=>$data), 200);
                exit;
            }else{

                App::_response(array("result"=>"error", "response"=>"Sorry, an error occurred updating user"), 500);
                exit;
            }

        }

    }


}




