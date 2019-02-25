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
                $controller->handleRegisterRequest();
                break;
            case 'products':
                require_once "controllers/products_controller.php";
                $controller = new ProductsController($this->model);
                $controller->handleProductsRequest();
                break;
            case 'detailProduct':
                require_once "controllers/products_controller.php";
                $controller = new ProductsController($this->model);
                $controller->handleDetailProductsRequest();
                break;
            case 'cart':
                require_once "controllers/products_controller.php";
                $controller = new ProductsController($this->model);
                $controller->handleCartRequest();
                break;
            case 'top5':
                require_once "controllers/products_controller.php";
                $controller = new ProductsController($this->model);
                $controller->handleTop5Request();
                break;
            case 'ajax':
                require_once "controllers/ajax_controller.php";
                $controller = new AjaxController($this->model);
                $controller->handleAjaxRequest();
                break;
        }
    }
}
