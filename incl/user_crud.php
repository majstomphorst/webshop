<?php

require_once "crud.php";

class userCRUD
{
    /** @var CRUD*/
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

    /**
     * read the database and returns all the products
     *
     * @return array->associtive['id' => string, 'name' => string,
     *                           'description' => string, 'price' => string,
     *                           'image_name' => string]
     */
    public function getProducts()
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
    public function getProductById($productId)
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
    public function storeOrder($orderInfo, $userId)
    {
        $sql = "INSERT INTO orders (user_id) VALUES(:user_id)";
        $values = array('user_id' => $userId);

        $order_id = $this->crud->createRow($sql, $values);

        $sql = "INSERT INTO orders_products (order_id, product_id, amount, price)
                            VALUES(:order_id,:productId,:amount,:unit_price)";

        foreach ($orderInfo as $orderRow) {
            $orderRow['order_id'] = $order_id;
            $this->crud->createRow($sql, $orderRow);
        }
    }

    /**
     *
     * @param productId int
     * @return void or throws
     */
    public function getTop5Sold()
    {
        $sql = "SELECT  products.name,
                        products.description,
                        products.image_name,
                        product_id,
                        SUM(amount) AS sum_amount
            FROM	    orders_products

            JOIN        orders ON orders_products.order_id = orders.id
            JOIN        products ON orders_products.product_id = products.id

            WHERE       orders.date > ADDDATE(NOW(),INTERVAL -7 DAY)

            GROUP BY    product_id
            ORDER BY	sum_amount DESC
            LIMIT       5;";

        $products = $this->crud->readMultiRows($sql);

        return $products;
    }

    public function getAvgProductRating($productIds)
    {
        $productIds = array("product_ids" => $productIds);

        $sql = "SELECT product_id, AVG(rating) as avgRating
                FROM ratings
                WHERE product_id IN (:product_ids) GROUP BY product_id";

        $productAvg = $this->crud->readMultiRows($sql, $productIds);

        return $productAvg;

    }

    public function getUserRating($userId)
    {
        $userId = array('user_id' => $userId);
        $sql = "SELECT product_id, rating FROM ratings WHERE user_id=:user_id";
        $userRating = $this->crud->readMultiRows($sql,$userId);
        return $userRating;
    }


    function updateOrStoreRating($productId,$rating,$userId)
    {
        $params = array('product_id' => $productId ,'user_id' => $userId, 'rating' => $rating);
        // check if user already rated
        $sql = "SELECT * FROM ratings WHERE product_id=$productId AND user_id=$userId";
        $result = $this->crud->readMultiRows($sql,$params);
        var_dump($result);


        if (count($result)  > 0 ) {
            // output data of each row
            $sql = "UPDATE ratings SET rating=:rating WHERE product_id=:product_id AND user_id=:user_id";
            $this->crud->updateRow($sql,$params);
        } else {
            $sql = "INSERT INTO ratings (product_id, user_id, rating) 
                    VALUES(:product_id, :user_id, :rating)";
            $this->crud->createRow($sql,$params);
        }
    }

}

$crud = new CRUD();
$user_crud = new userCRUD($crud);
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

$user_crud->updateOrStoreRating(1,5,13);
