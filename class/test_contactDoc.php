<?php
include_once "contact_doc.php";

$data = array('page' => 'home');
$data['menuLeft'] = array('home' => 'Home', 'about' => 'About', 'contact' => 'Contact', 'products' => 'Products', 'top5' => 'Top 5');
if (true) {
    $data['loggedIn'] = true;
    $data['menuLeft']['cart'] = 'ShoppingCart';
    $username = "Henk"; //getLoggedInUserName();
    $data['menuRight'] = array('logout' => 'Logout ' . ucfirst($username));
} else {
    $data['loggedIn'] = false;
    $data['menuRight'] = array('login' => 'Login', 'register' => 'Register');
}

$title = "Contact";
$fields[0] = array("type"=>"text", "name"=>"name", "placeHolder"=>"name Please:","value"=>"");
$fields[1] = array("type"=>"email", "name"=>"name", "placeHolder"=>"email please:","value"=>"");
$fields[2] = array("type"=>"password", "name"=>"name", "placeHolder"=>"password please:","value"=>"");


$view = new ContactDoc($data,$title,$fields);
$view->show();

?>