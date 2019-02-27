<?php
session_start();

/**
* login user stores a username in the session
* @param associtive 
* @return void 
* 
*/
function login($userInfo)
{
    $_SESSION['user'] = $userInfo;
}

/**
* get the username or null if there is no user
*
* @return string username or nill
* 
*/
function getLoggedInUserName() {
    
    if (isLoggedIn()) {
        return $_SESSION['user']->name;
    } else {
        return null;
    }
}
/**
* get the usernameId or null if there is no user
*
* @return int userId or null
* 
*/
function getLoggedInUserId() {
    if (isLoggedIn()) {
        return $_SESSION['user']->id;;
    } else {
        return null;
    }
}

/**
* check if a user is logged in
*
* @return bool true is a user is logged in false otherwise
* 
*/
function isLoggedIn()
{
    if (isset($_SESSION['user'])) {
        return true;
    } else {
        return false;
    }
}

/**
* logout 
*
* @return void
* 
*/
function logout()
{
    // remove all session variables
    session_unset(); 
    // destroy the session 
    session_destroy();
}

/**
* returns the cart
*
* @return array
*/
function getCart()
{
    //check for cart
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    } 
    return $_SESSION["cart"];
}

/**
* adds or subtracks the amount from the cart
* 
* @param int $productId 
* @param int $amount
* @return void
*/
function mutateToCart(int $productId, $amount)
{
    if (array_key_exists($productId, getCart())) {
        $_SESSION['cart'][$productId] += $amount;

        if ($_SESSION['cart'][$productId] <= 0) {
            unset($_SESSION['cart'][$productId]);
        }
    } else {
        $_SESSION['cart'][$productId] = $amount;
    }
}

/**
* check if a cart exists
* 
* @return bool true if a cart exist otherwise false
*/
function doesCartExist()
{
    if (array_key_exists('cart',$_SESSION)) {
        return true;
    } else {
        return false;
    }
}

function removeCart() {
    unset($_SESSION['cart']);
}

?>