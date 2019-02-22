<?php
require_once "models/page_model.php";

class PageController
{
    private $model;

    public function __construct() 
    {
        $this->model = new PageModel();
    }

    public function handleRequest() 
    {
        $this->model->getRequestedPage();

        switch ($this->model->requested_page) {
            case 'home':
                require_once 'classes/home_doc.php';
                $view = new HomeDoc($this->model);
                $view->show();
                break;
            case 'about':
                require_once 'classes/about_doc.php';
                $view = new AboutDoc($this->model);
                $view->show();
                break;
            case 'contact':
                require_once 'controllers/contact_controller.php';
                $controller = new ContactController($this->model);
                $controller->handleContactRequest();
                break;
            case 'login':
                require_once "controllers/user_controller.php";
                $controller = new UserController($this->model);
                $controller->handleLoginRequest();
                break;
            case 'logout':
                require_once "controllers/user_controller.php";
                $controller = new UserController($this->model);
                $controller->handleLogOutRequest();
                break;
            case 'register':
                require_once "controllers/user_controller.php";
                $controller = new UserController($this->model);
                $controller->handelRegisterRequest();
                break;





            case 'products':
                // require_once 'controllers/product_controller.php';
                // $controller = new ProductController($this->model);
                // $controller->
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


}
