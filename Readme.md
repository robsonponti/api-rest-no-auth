#Users Api REST

#Introduction

This API has only demonstrative purpose, it´s an API used to fetch, edit or remove users data from server.

#Overview

    Please, for testing use Postman, Insomnia or another API tool your preference. It´s built in PHP 7.4.8 and MySQL MariaDB, all POST requests should be sent in JSON, all responses you will get in JSON.

    Important: For using the API you need to change database connection parameters in lib\DB.php file for your mysql credentials and database name.

You don´t need to change anything more, all endpoint will be read dynamically.

#Authentication
    
    This API has no any security layer implemented it´s only demonstrative purpose.

#Documentation

#Endpoints:

    GET /api/users/get/{$id}
    GET /api/users/getAll
    POST /api/users/create
    PUT /api/users/update
    DELETE /api/users/delete/{$id}


All endpoints should contain a valid action as shown above otherwise you will get a 400 Bad Request.

Invalid request example:

Request:

    GET /api/users/ HTTP/1.1
    Host: hostname

    Response:
        HTTP Status 400 Bad Request
            {
                "status": "error",
                "response": "Invalid endpoint"
            }


#Fetching user data about user

Request:

    GET /api/users/get/{$id} HTTP/1.1
    Host: hostname

Response:

    Returning all user data

        HTTP Status 200 OK
            {
                "id": 3,
                "nome": "José Silva",
                "email": "jose.s@example.com",
                "gender": "Male",
                "birthdate": "1975-10-02"
            }

    Request ok but user not found 

        HTTP Status 200 OK 
            {
                "result": "error",
                "response": "Sorry, user not found"
            }

        Id is not present or is not a integer
        HTTP Status 400 Bad Request  
            {
                "result": "error",
                "response": "Parameter id is missing or invalid"
            }




#Fetching all users data

    Request: GET /api/users/getAll HTTP/1.1
    Host: hostname

Response:

    Success returning all users data

        HTTP Status 200 OK
            {
            "result": "success",
            "data": [
                {
                "id": 2,
                "name": "Maria Silva",
                "email": "maria.s@example.com",
                "gender": "Female",
                "birthday": "1985-07-10"
                },
                {
                "id": 3,
                "name": "José Silva",
                "email": "jose.s@example.com",
                "gender": "Male",
                "birthday": "1975-10-02"
                }
            ]
            }

        Request ok but there is no data to show

        HTTP Status 200 OK
            {
            "result": "success",
            "response": "Sorry, there is no users to show"
            }





#Create a new user

    POST /api/users/create HTTP/1.1
    Host: hostname
    Content-Type: application/json

        Request:
            {
                "name": "Name",
                "gender":"integer",
                "email":"example@example.com",
                "birthdate":"YYYY-MM-DD"
            }

#Parameters

    name: string
    gender: integer 1-Female or 2-Male 
    email: string
    birthdate: date in format YYYY-MM-DD


Response:

    Success creating user

        HTTP Status 200 OK
            {
            "result": "success",
            "response": "User created sucessfuly",
            "id": "4"
            }

        If any field it is empty or invalid type data you will receive band request

        HTTP Status 400 Bad Request
            {
            "result": "error",
            "response": "Parameter missing"
            }



#Updating an user

Request:

    PUT /api/users/update HTTP/1.1
    Host: hostname
        Content-Type: application/json
            {   
                "id":3,
                "name": "José S.",
                "gender":2,
                "email":"jose.s@example.com",
                "birthdate":"1975-10-02"
            }


Response:

    Success updating user data
        HTTP Status 200 OK
            {
            "result": "success",
            "response": "User updated sucessfuly",
            "data": {
                "id": 3,
                "nome": "José S.",
                "email": "jose.s@example.com",
                "gender": "Male",
                "birthdate": "1975-10-02"
            }
            }

    If an invalid id or user not exists 
        HTTP Status 200 OK
            {
            "result": "error",
            "response": "User not found"
            }

    If a parameter is not present on request body or it´s invalid data
        HTTP Status 400 Bad Request
            {
            "result": "error",
            "response": "Parameter missing"
            }



#Deleting an user

    Request:
        DELETE /api/users/delete/{$id} HTTP/1.1
        Host: hostname

Response:

    User deleted successfuly
        HTTP Status 200 OK
            {
            "result": "success",
            "response": "User removed successfuly"
            }


    Request ok but user doesn´t exists
        HTTP Status 200 OK
            {
            "result": "error",
            "response": "User not found"
            }

    Id is not present or is not a integer
        HTTP Status 400 Bad Request
            {
            "result": "error",
            "response": "Parameter id is missing or invalid"
            }

    Error removing user
        HTTP Status 500
            {
                "result": "error",
                "response": "Error deleting user"
            }


If you want to see documentation please check out [here](https://documenter.getpostman.com/view/13425265/TWDTLdyX#f0e99e0c-f8a0-4990-8f48-a1c79fc9ed24)
