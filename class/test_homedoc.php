<?php
    include_once "home_doc.php";

    $data = array ( 'page' => 'home' );
    $data['menuLeft'] = array('home' => 'Home','about' => 'About', 'contact' => 'Contact', 'products' => 'Products', 'top5' => 'Top 5');
    if (true) {
        $data['loggedIn'] = true;
        $data['menuLeft']['cart'] = 'ShoppingCart';
        $username = "Henk";//getLoggedInUserName();
        $data['menuRight'] = array('logout' => 'Logout '. ucfirst($username));
    } else {
        $data['loggedIn'] = false;
        $data['menuRight'] = array('login' => 'Login', 'register' => 'Register');
    }



    $view = new HomeDoc($data);
    $view  -> show();

?>