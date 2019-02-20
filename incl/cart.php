<?php
require_once 'incl/database.php';
require_once 'incl/money_format.php';


/**
* mutates the cart
* 1. adds a new product to the cart with a order amount of 1
* 2. adds 1 to the already order product
* 3. subtracks 1 form the already order product if the amount get to 0 
* the product is removed from the cart
*
* @return void
*
*/
function handleCartActions()
{
    // get a dictionary with the order information
    $order = getPostVar('order');

    switch ($order['action']) {
        case 'addToCart':
            $id = $order['productId'];

            mutateToCart($id,1);
            break;
        
        case 'removeFromCart':
            $amount = -1;
            $id = $order['productId'];

            mutateToCart($id,-1);
            break;

        case 'placeOrder':
            $orderInfo = prepareOrderInfoForStorage();
            $userId = getLoggedInUserId();
            if (storeOrder($orderInfo,$userId)) {
                removeCart();
            }
            break;
    }
}


/**
* collect all the information needed to store the order in the db
*
* @return associtive orderInfo array[0 => array['productId','amount','unit_price'],
*                                    1 => array[],
*                                    'total_price']
* 
*/
function prepareOrderInfoForStorage(){

    $cart = getCart();
    $orderInfo = array();
    $total_price = 0;

    foreach ($cart as $productId => $amount) {
        $productInfo = getProductById($productId);

        $cartRow['productId'] = $productId;
        $cartRow['amount'] = $amount;
        $cartRow['unit_price'] = $productInfo['price'];
        $total_price += $amount * $productInfo['price'];
        
        array_push($orderInfo ,$cartRow);
    }
    $orderInfo['total_price'] = $total_price;
    return $orderInfo;
}

/**
* reads the database and looks up a user by there email
*
* @param   String email to look up in the database
* @return  associtive array['name','email','password']
* 
*/
function prepareShoppingCart()
{
    $cart = getCart();
    $cartRows = array();

    foreach ($cart as $productId => $amount) {
        $cartRow = array('product' => getProductById($productId));
        $cartRow['amount'] = intval($amount);
        $cartRow['total'] = floatval($cartRow['product']['price']) * $cartRow['amount'];
        array_push($cartRows ,$cartRow);
    }
    return array('cart' => $cartRows);
}

function showCart($data) 
{
    echo '
    <div class="container">
        <div class="card shadow my-5">
            <div class="card-body p-5">
            <table class="table">
                <thead class="thead-light">
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Total</th>
                </tr>
                </thead>
                <tbody>';
                if (array_key_exists('cart', $data)) {
                    foreach ($data['cart'] as $cartRow) {
                        showRow($cartRow);
                    }
                }
            echo '</tbody>
            </table>

            <form action="index.php" method="post">
                <input type="hidden" name="page" value="cart">
                <input type="hidden" name="order[action]" value="placeOrder">
                <button type="submit" name="order[productId]" class="btn btn-success">Order!</button>
            </form> 
            </div>
        </div>
    </div>
    ';
}

function showRow($cartRow) {
    echo '
    <tr>
        <th>'. $cartRow['product']['name'] .'</th>
        <th>&euro; '.money_format('%.2n', $cartRow['product']['price']) .'</th>
        <th>
            <div class="row">
                <div class="col-sm">
                    <form class="form-inline" action="index.php" method="post">
                        <input type="hidden" name="page" value="cart">
                        <input type="hidden" name="order[action]" value="removeFromCart">
                        <button value="'.$cartRow['product']['id'].'" type="submit" name="order[productId]" class="btn btn-outline-danger btn-block">&#8678;</button>
                    </form>
                </div>
                <div class="col-sm col-5">
                <p class="text-center">'. $cartRow['amount'] .'</p>
    
                </div>
                <div class="col-sm">
                    <form class="form-inline" action="index.php" method="post">
                        <input type="hidden" name="page" value="cart">
                        <input type="hidden" name="order[action]" value="addToCart">
                        <button value="'.$cartRow['product']['id'].'" type="submit" name="order[productId]" class="btn btn-outline-success btn-block">&#8680;</button>
                    </form>
                </div>
            </div>
        </th>
        <th>&#8364; '. money_format('%.2n', $cartRow['total']).'</th>
    </tr>
    ';

}
?>