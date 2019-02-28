<?php
require_once "crud.php";

/**
 * handles the databae functions for the shop.
 */
class ShopCRUD
{
    /** @var CRUD */
    private $crud = null;

    public function __construct($cruid)
    {
        $this->crud = $cruid;
    }

    /**
     * read the database and returns all the products
     *
     * @return array(0 => object,1 => object);
     *                object->id = string, object->name => string,
     *                object->description = string, object->price => string,
     *                object->image_name = string
     * @hrows
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
     * @param int productId
     * @return object
     *
     * (object.id = String, object.name => string,
     *  object.description = string, object.price => string,
     *  object.image_name = string)
     */
    public function getProductById($productId)
    {
        $sql = "SELECT * FROM products WHERE id=:id";
        $values = array('id' => $productId);
        $product = $this->crud->readOneRow($sql, $values);
        return $product;
    }

    /**
     * @param array orderInfo array(0 => array('productId','amount','unit_price'),
     *                              1 => array[])
     * @param int productId
     *
     * @return void
     * @throws
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
        return true;
    }

    /**
     * gets the top 5 most sold products in the last 7 days
     * 
     * @param void
     *
     * @return array(0 => object products)
     * @throws
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

    /**
     * get the avg product ratings by the products id
     * @param array int array(0,2,3,5);
     *
     * @return array objects 
     * @throws
     */
    public function getAvgProductRating($productIds)
    {
        $productIds = array("product_ids" => $productIds);

        $sql = "SELECT product_id, AVG(rating) as avgRating
                FROM ratings
                WHERE product_id IN (:product_ids) GROUP BY product_id";

        $products = $this->crud->readMultiRows($sql, $productIds);

        return $products;
    }

    /**
     * gets the ratings the user hase given.
     * 
     * @param int $userId
     * 
     * @return array objects
     */
    public function getUserRating($userId)
    {
        $userId = array('user_id' => $userId);
        $sql = "SELECT product_id, rating FROM ratings WHERE user_id=:user_id";
        $userRating = $this->crud->readMultiRows($sql, $userId);
        return $userRating;
    }

    /**
     * this inserts or updates a product rating from a user in the database
     * @param int productId
     * @param int rating
     * @param int userId
     * 
     * @return void
     * @throws 
     */
    public function updateOrStoreRating($productId, $rating, $userId)
    {
        $params = array('product_id' => $productId, 'user_id' => $userId, 'rating' => $rating);
        // check if user already rated
        $sql = "SELECT * FROM ratings WHERE product_id=$productId AND user_id=$userId";
        $result = $this->crud->readMultiRows($sql, $params);

        if (count($result) > 0) {
            // output data of each row
            $sql = "UPDATE ratings SET rating=:rating WHERE product_id=:product_id AND user_id=:user_id";
            $this->crud->updateRow($sql, $params);
        } else {
            $sql = "INSERT INTO ratings (product_id, user_id, rating)
                    VALUES(:product_id, :user_id, :rating)";
            $this->crud->createRow($sql, $params);
        }
    }

}
