<?php

require_once "crud.php";

class userCRUD
{
    private $crud = null;
    public function __construct(CRUD $crud)
    {
        $this->crud = $crud;
    }

    /**
    * writes a user to the database
    *
    * @param    String name to look up in the database
    * @param    String email to look up in the database
    * @param    String password to look up in the database
    * @throws   Exception when failed to store user
    */
    function storeUser(String $name, String $email, String $password)
    {
        $sql = "INSERT INTO users (name, email, password)
                VALUES (:name, :email, :password)";
        $values = array('name'=> $name, 'email'=> $email, 'password' => $password);
        
        $this->crud->createRow($sql,$values);
        
    }
}
$crud = new CRUD();
$user_crud = new userCRUD($crud);
$user_crud->storeUser('pim','pim@pim.com','klaasvaak');