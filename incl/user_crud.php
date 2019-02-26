<?php

require_once "crud.php";

class userCRUD
{
    private $crud = null;

    public function __construct()
    {
        $this->crud = new CRUD();
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
    function findUserByEmail(String $email)
    {
        $userInfo = null;
        $sql = "SELECT * FROM users WHERE email = :email";
        $values = array('email' => $email);

        $userInfo = $this->crud->readOneRow($sql, $values);

        return $userInfo;
    }


    /**
    * read the database and returns all the products
    *
    * @return array->associtive['id' => string, 'name' => string,
    *                           'description' => string, 'price' => string,
    *                           'image_name' => string]
    */
    function getProducts()
    {
        $sql = "SELECT * FROM products";
        $products = $this->crud->readMultiRows($sql);

        return $products;
    }

    /**
    * read the database and returns all the products
    *
    * @param productId
    * @return array->associtive['id' => string, 'name' => string,
    *                           'description' => string, 'price' => string,
    *                           'image_name' => string]
    */
    function getProductById($productId)
    {
        $sql = "SELECT * FROM products WHERE id=:id";
        $values = array('id' => $productId);
        $product = $this->crud->readOneRow($sql, $values);
        return $product;
    }

    /**
    *
    * @param orderInfo array[0 => array['productId','amount','unit_price'],
    *                        1 => array[],
    *                        'total_price' => ]
    * @param productId int
    * @return void or throws
    */
    function storeOrder($orderInfo, $userId)
    {
        unset($orderInfo['total_price']);

        $sql = "INSERT INTO orders (user_id) VALUES(:user_id)";
        $values = array('user_id' => $userId);

        $order_id = $this->crud->createRow($sql,$values);

        $sql =  "INSERT INTO orders_products (order_id, product_id, amount, price) 
                            VALUES(:orders_id,:product_id,:amount,:price)";

        foreach ($orderInfo as $orderRow) {
            $orderRow['order_id'] = $order_id;
            $this->crud->createRow($sql,$orderRow);
        }




        try {

            $orders_id = mysqli_insert_id($conn);
            

            foreach ($orderInfo as $orderRow) {

                $product_id = $orderRow['productId'];
                $amount = $orderRow['amount'];
                $price = $orderRow['unit_price'];

                $sql =  "INSERT INTO orders_products (order_id, product_id, amount, price) 
                            VALUES('$orders_id','$product_id','$amount',$price)";

                if (!mysqli_query($conn,$sql)) {
                    throw new Exception("Insertion order_product failed: " . mysqli_error($conn), 4);
                }
            }
            return true;

        } finally {
            mysqli_close($conn);
        }

    }

}




// $crud = new CRUD();
$user_crud = new userCRUD();
// $user_crud->storeUser('pim','tak@abc.com','klaasvaak');

// $result = $user_crud->findUserByEmail('kip@kip.com');
// var_dump($result);

// $result = $user_crud->getProducts();
// var_dump($result);


// $result = $user_crud->getProductById(2);
// var_dump($result);
