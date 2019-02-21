<?php
require_once 'incl/data_storage.php';
require_once 'incl/session_manager.php';

require_once 'controllers/page_controller.php';

$pageController = new PageController();

$pageController->handleRequest();

// romve everything
exit();


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

                    if (registerUser($data['name'], $data['email'], $data['password'])) {
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

                        if (validateUser($data['email'], $data['password'])) {

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
    $data['menuLeft'] = array('home' => 'Home', 'about' => 'About', 'contact' => 'Contact', 'products' => 'Products', 'top5' => 'Top 5');

    if (isLoggedIn()) {
        $data['loggedIn'] = true;
        $data['menuLeft']['cart'] = 'ShoppingCart';
        $username = getLoggedInUserName();
        $data['menuRight'] = array('logout' => 'Logout ' . ucfirst($username));
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
        case 'products':
            require_once 'classes/products_doc.php';
            $view = new ProductsDoc($data);
            $view->show();
            break;
        case 'top5':
            require_once 'classes/top5_doc.php';
            $view = new Top5Doc($data);
            $view->show();
            break;
        case 'cart':
            require_once 'classes/cart_doc.php';
            $view = new CartDoc($data);
            $view->show();
            break;
        case 'login':
            require_once 'classes/login_doc.php';
            $view = new LoginDoc($data);
            $view->show();
            break;
        case 'register':
            require_once 'classes/register_doc.php';
            $view = new RegisterDoc($data);
            $view->show();
            break;
        case 'detailProduct':
            require_once 'classes/detail_product_doc.php';
            $view = new DetailProductDoc($data);
            $view->show();
            break;
        default:
            var_dump('NOO!');
            break;
    }

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

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }