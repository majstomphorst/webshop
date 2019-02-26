<?php

require_once "crud.php";

/**
 * 
 */
class userCRUD
{
    /** @var CRUD */
    private $crud = null;

    public function __construct($cruid)
    {
        $this->crud = $cruid;
    }

    /**
     * writes a user to the database
     *
     * @param    String name to look up in the database
     * @param    String email to look up in the database
     * @param    String password to look up in the database
     * @throws   Exception when failed to store user
     */
    public function storeUser(String $name, String $email, String $password)
    {
        $sql = "INSERT INTO users (name, email, password)
                VALUES (:name, :email, :password)";
        $values = array('name' => $name, 'email' => $email, 'password' => $password);

        $this->crud->createRow($sql, $values);
    }

    /**
     * reads the database and looks up a user by there email
     *
     * @param   String email to look up in the database
     * @return  UserInfo UserInfo['id','name','email','password'] if found
     * or null otherwise
     *
     */
    public function findUserByEmail(String $email)
    {
        $userInfo = null;
        $sql = "SELECT * FROM users WHERE email = :email";
        $values = array('email' => $email);

        $userInfo = $this->crud->readOneRow($sql, $values);

        return $userInfo;
    }
}

// $crud = new CRUD();
// $user_crud = new userCRUD($crud);
// $user_crud->storeUser('pim','tak@abc.com','klaasvaak');

// $result = $user_crud->findUserByEmail('kip@kip.com');
// var_dump($result);

// $result = $user_crud->getProducts();
// var_dump($result);

// $result = $user_crud->getProductById(2);
// var_dump($result);

// $order = array('0' => array(
//     'productId' => 4,
//     'amount' => 3,
//     'unit_price' => '10.000',
// ));
// $user_id = 13;

// $user_crud->storeOrder($order,$user_id);

// $i = $user_crud->getTop5Sold();
// var_dump($i);

// $ids = array('0' => '1',
//     '1' => '2',
//     '2' => '3',
//     '3' => '4',
//     '4' => '5',
// );
// $productAvg = $user_crud->getAvgProductRating($ids);
// var_dump($productAvg);

// $i = $user_crud->getUserRating('13');
// var_dump($i);

// $user_crud->updateOrStoreRating(1,5,13);
