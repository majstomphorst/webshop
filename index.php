<?php
require_once 'incl/data_storage.php';
require_once 'incl/session_manager.php';

// main app
$page = getRequestedPage();
// proces request
$data = procesRequest($page);
// show page
showResponsePage($data);

function procesRequest($page)
{
    $page = test_input($page);
    $isPost = $_SERVER['REQUEST_METHOD'] == 'POST';

    $data = array();

    switch ($page) {
        case 'contact':
            require_once 'incl/contact.php';

            if ($isPost) {
                $data = validateForm();
                if (getArrayVar($data, 'valid', false)) {
                    $page = 'contact';
                }
            }
            break;
        case 'register':
            require_once 'incl/register.php';
            if ($isPost) {

                try {
                    $data = validateRegisterForm();

                    if (registerUser($data['name'],$data['email'],$data['password'])) {
                        $page = 'login';
                    } else {
                        $data["userExists"] = 'user is allready in database';
                    }
                } catch (\Throwable $th) {
                    $data['errorMessage'] = $th->getMessage();
                    $page = 'home';
                } 
            } 
            break;
        case 'login':
            require_once 'incl/login.php';
            if ($isPost) {

                $data = validateLoginForm();

                try {
                    // check if the login form is complete
                    if (getArrayVar($data, 'valid', false)) {

                        if (validateUser($data['email'],$data['password'])) {

                            $userInfo = findUserByEmail($data['email']);
                            login($userInfo);
                            $page = 'home';
                            
                        } else {
                            $data['LoginErr'] = "Email and password combination invalid";
                        }
                    }

                } catch (\Throwable $th) {
                    $data['errorMessage'] = $th->getMessage();
                    $page = 'home';
                }

            }
            break;
        case 'logout':
            logOut();

            $page = 'home';
            break;
        case 'products':
            try {
                $data['products'] = getProducts();
            } catch (\Throwable $th) {
                $data['errorMessage'] = $th->getMessage();
                $page = 'home';
            }
            $page = 'products';   
            break;
        case 'detailProduct':
            try {
                $id = getUrlVar('id');
                if (empty($id)) {
                    $data['products'] = getProducts();
                    $page = 'products';
                    break;
                }

                $data['product'] = getProductById($id);

            } catch (\Throwable $th) {
                $data['errorMessage'] = $th->getMessage();
                $page = 'home';
            }
            
            break;
        case 'cart':
            require_once 'incl/cart.php';
            if ($isPost) {
                handleCartActions();
            }

            $data = prepareShoppingCart();
            $page = 'cart';
            break;
        case 'top5':
            $data['top5'] = getTop5Sold();
            $page = 'top5';
            break;
            
        case 'ajax':
            require_once 'incl/ajax.php';
            
            if ($isPost) {
                handleAjaxActions();
            }
            exit();
            break;
    }


    $data['page'] = $page;
    $data['menuLeft'] = array('home' => 'Home','about' => 'About', 'contact' => 'Contact', 'products' => 'Products', 'top5' => 'Top 5');
    
    if (isLoggedIn()) {
        $data['loggedIn'] = true;
        $data['menuLeft']['cart'] = 'ShoppingCart';
        $username = getLoggedInUserName();
        $data['menuRight'] = array('logout' => 'Logout '. ucfirst($username));
    } else {
        $data['loggedIn'] = false;
        $data['menuRight'] = array('login' => 'Login', 'register' => 'Register');
    }

    return $data;

}

// returns the name(string) of the requested page
function getRequestedPage()
{
    $requested_type = $_SERVER['REQUEST_METHOD'];
    if ($requested_type == 'POST') {
        $requested_page = getPostVar('page', 'home');
    } else {
        $requested_page = getUrlVar('page', 'home');
    }
    return $requested_page;
}

// echo's the html page on screen
function showResponsePage($data)
{
    switch ($data["page"]) {
        case 'home':
            require_once 'classes/home_doc.php';
            $view = new HomeDoc($data);
            $view->show();
            break;
        case 'about':
            require_once 'classes/about_doc.php';
            $view = new AboutDoc($data);
            $view->show();
            break;
        case 'contact':
            require_once 'classes/contact_doc.php';
            $view = new ContactDoc($data);
            $view->show();
            break;
        case 'login':
            require_once 'classes/login_doc.php';
            $view = new LoginDoc($data);
            $view->show();
            break;
        case 'register':
            break;
        
        default:
        var_dump('NOO!');
            beginDocument();
            showHeadSection();
            showBodySection($data);
            break;
    }



}

// echo's the body of the html
function showBodySection($data)
{
    echo '<body>';
    messagePage($data);
    showMenu($data);
    showContent($data);
    showFooter();
    includeBoodstrapJavaScript();
    echo '</body>';
}

// get the form data that is posted to the server or returns the default
function getPostVar($key, $default = '')
{
    return isset($_POST[$key]) ? $_POST[$key] : $default;
}

// extracts the requested page (string) or the default
function getUrlVar($key, $default = '')
{
    return isset($_GET[$key]) ? $_GET[$key] : $default;
}

// pulls the data from a array by its key of returns the default
function getArrayVar($arry, $key, $default = '')
{
    return isset($arry[$key]) ? $arry[$key] : $default;
}

// loads the requested body of the page
function showContent($data)
{
    switch ($data['page']) {
        case 'home':
            require_once 'incl/home.php';
            showHome($data);
            break;
        case 'about':
            require_once 'incl/about.php';
            showAbout($data);
            break;
        case 'contact':
            require_once 'incl/contact.php';
            showForm($data);
            break;
        case 'login':
            require_once 'incl/login.php';
            showLoginForm($data);
            break;
        case 'register':
            require_once 'incl/register.php';
            showRegisterForm($data);
            break;
        case 'products':
            require_once 'incl/products.php';
            showProducts($data);
            break;
        case 'detailProduct':
            require_once 'incl/detail_products.php';
            showDetailProduct($data);
            break;
        case 'cart':
            require_once 'incl/cart.php';
            showCart($data);
            break;
        case 'top5':
            require_once 'incl/top5.php';
            ShowTop5($data);
            break;
        default:
            showError("Page [" . $data['page'] . "] not found."); 
            break;
    }
}

function beginDocument()
{ 
    echo
        '<!doctype html>
            <html lang=en>';
}


function showHeadSection()
{
    echo '<head>
            <!-- Required meta tags -->
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

            <!-- CSS -->
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
            <link rel="stylesheet" href="assets/css/mystyle.css">

            <!-- Font Awesome Icon Library -->
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

            <title>Opdracht 2.1</title>
            <meta name="author" content="Maxim Stomphorst"/>
        </head>';
}

function messagePage($data)
{
    if (isset($data['errorMessage'])) {
            echo '
            <div class="container">
            <div class="alert alert-info" role="alert">
            <strong>Message:</strong><br>
                '.$data['errorMessage'].'
            </div>
            </div>';
    }
   
}

function showMenu($data)
{
    echo'
    <nav class="navbar navbar-expand-lg navbar-light bg-light static-top shadow">
        <div class="navbar-nav">

            <a class="navbar-brand" href="index.php?page=home">
                <img src="assets/images/logo.png" width="30" height="30" alt="">
            </a>

            <a class="navbar-brand" href="#">Educom</a>

            <ul class="navbar-nav mr-auto">';
                foreach ($data['menuLeft'] as $pageLink => $buttonText) {
                    showMenuItem($pageLink,$buttonText,$data['page']);
                }
    echo'   </ul> 
        </div>
        <div class="navbar-nav ml-auto">
            <ul class="navbar-nav mr-auto">';
                foreach ($data['menuRight'] as $pageLink => $buttonText) {
                    showMenuItem($pageLink,$buttonText,$data['page']);
                }
        echo'</ul>
            </div>
    </nav>';
}

function showMenuItem($pageLink, $buttonText, $currentPage)
{
    if ($pageLink == $currentPage) {
        echo '
        <li class="nav-item">
            <a class="nav-link nav-link active" href="index.php?page='. $pageLink .'">'. $buttonText .'</a>
        </li>';
    } else {
    echo '
        <li class="nav-item">
            <a class="nav-link" href="index.php?page='. $pageLink .'">'. $buttonText .'</a>
        </li>';
    }
}

function showFooter()
{
    echo
        '
        <footer class=" py-3 bg-dark">
            <p class="m-0 text-center text-white">Copyright &copy; 2019, Maxim Stomphorst</p>
        </footer>';
}

function includeBoodstrapJavaScript()
{
    echo
        '<!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src ="assets/js/jquery-3.3.1.js"></script>

        <!-- javaStrip -->
        <script src="assets/js/ratings.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut"
            crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k"
            crossorigin="anonymous"></script>';
}

function showError($message)
{
    echo $message;
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }