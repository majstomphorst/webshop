<?php
define('dbHost',"localhost");
define('dbName',"educom");
define('dbUser',"php_user");
define('dbPassword',"password");


/**
* creats a connection with the database, throw an error ofhterwise
*
* @return connection
*/
function connectToEducomDatabase()
{
    // Create connection
    $conn = mysqli_connect(dbHost, dbUser, dbPassword, dbName);
    // Check connection
    if (!$conn) {
        throw new Exception("Connection failed: " . mysqli_connect_error(), 1);
    }
    return $conn;
}


/**
* reads the database and looks up a user by there email
*
* @param   String email to look up in the database
* @return  associtive array['name','email','password'] if found
* or null otherwise
* 
*/
function findUserByEmail(String $email)
{   
    $userInfo = null;
    $conn = connectToEducomDatabase();
    $sql = "SELECT * FROM users WHERE email = '".$email."'";
    $result = mysqli_query($conn,$sql);

    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        $userInfo = mysqli_fetch_assoc($result);
    }
    
    mysqli_close($conn);
    return $userInfo;
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
    $conn = connectToEducomDatabase();
    try {
        $sql = "INSERT INTO users (name, email, password)
                VALUES ('".$name."', '".$email."' ,'".$password."')";

        if (!mysqli_query($conn,$sql)) {
            throw new Exception("Insertion user failed: " . mysqli_error($conn), 2);
        }

    } finally {
        mysqli_close($conn);
    }
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
    $conn = connectToEducomDatabase();
    try {
        $sql = "SELECT * FROM products";
        $result = mysqli_query($conn,$sql);

        if (!$result) {
            throw new Exception("Selection products failed: " . mysqli_error($conn), 2);
        }
    
        $products = array();
        // Fetch all results
        while($row = mysqli_fetch_assoc($result)) {
            $products[$row['id']] = $row;
        }
        return $products;

    } finally {
        mysqli_close($conn);
    }
    
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
    $conn = connectToEducomDatabase();
    try {
        $sql = "SELECT * FROM products WHERE id='$productId'";
        $result = mysqli_query($conn,$sql);
        
        
        if (!$result) {
            throw new Exception("Selection products by id failed: " . mysqli_error($conn), 3);
        } elseif (mysqli_num_rows($result) <= 0) {
            throw new Exception("No product found.", 4);
            
        }
    
        $product = array();
        // Fetch all results
        $product = mysqli_fetch_assoc($result);
        return $product;
    } finally {
        mysqli_close($conn);
    }
}

/**
*
* @param orderInfo array[0 => array['productId','amount','unit_price'],
*                        1 => array[],
*                        'total_price']
* @param productId int
* @return void or throws
*/
function storeOrder($orderInfo, $userId)
{

    $conn = connectToEducomDatabase();
    $total_price = $orderInfo['total_price'];

    try {
        $sql = "INSERT INTO orders (user_id, total_price)
                VALUES('".$userId."','$total_price')";

        if (!mysqli_query($conn,$sql)) {
            throw new Exception("Insertion order failed: " . mysqli_error($conn), 2);
        }

        $orders_id = mysqli_insert_id($conn);

        foreach ($orderInfo as $orderRow) {

            // the orderInfo includes total_price on the last line
            // this is not a new product order so if there is no id stop
            if (empty($orderRow['productId'])) {
                return false;
            }
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

/**
*
*
* @param productId int
* @return void or throws
*/
function getTop5Sold()
{
    $conn = connectToEducomDatabase();
    try {
        $sql = "SELECT      products.name,
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

        $result = mysqli_query($conn,$sql);

        if (!$result) {
            throw new Exception("Selection top5 failed: " . mysqli_error($conn), 6);
        }
    
        $products = array();
        // Fetch all results
        while($row = mysqli_fetch_assoc($result)) {
            array_push($products,$row);
        }
        return $products;

    } finally {
        mysqli_close($conn);
    }
}

function getAvgProductRating($productIds)
{
    $str = implode(", ", $productIds);
    $productAvg = array();

    $conn = connectToEducomDatabase();

    try {
        
        $sql = "SELECT product_id, AVG(rating) as avgRating FROM ratings WHERE product_id IN ($str) GROUP BY product_id";
        
        $result = mysqli_query($conn,$sql);
        // Fetch all results
        
        while($row = mysqli_fetch_assoc($result)) {
            array_push($productAvg,$row);
        }

        if (!$result) {
            throw new Exception("Insertion product rating failed: " . mysqli_error($conn), 2);
        }

    } finally {
        mysqli_close($conn);
    }
    return $productAvg;
    
}

function getUserRating($productIds,$userId)
{
    $userRating = array();

    $conn = connectToEducomDatabase();

    try {
        
        $sql = "SELECT product_id, rating FROM ratings WHERE user_id=$userId";
        
        $result = mysqli_query($conn,$sql);
        // Fetch all results
        
        while($row = mysqli_fetch_assoc($result)) {
            // array_push($userRating,$row);
            $userRating[$row['product_id']] = $row['rating'];
        }

        if (!$result) {
            throw new Exception("Insertion product rating failed: " . mysqli_error($conn), 2);
        }

    } finally {
        mysqli_close($conn);
    }
    return $userRating;
}

function updateOrStoreRating($productId,$rating,$userId)
{
    $conn = connectToEducomDatabase();
    // check if user already rated
    $sql = "SELECT * FROM ratings WHERE product_id=$productId AND user_id=$userId";
    
    $result = mysqli_query($conn,$sql);

    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        $sql = "UPDATE ratings SET rating=$rating WHERE product_id=$productId AND user_id=$userId";
    } else {
        $sql = "INSERT INTO ratings (product_id,user_id,rating) 
        VALUES('$productId','$userId','$rating')";
    }

    try {
        if (!mysqli_query($conn,$sql)) {
            throw new Exception("opdate or store rating failed: " . mysqli_error($conn), 2);
        }

    } finally {
        mysqli_close($conn);
    }
}


?>