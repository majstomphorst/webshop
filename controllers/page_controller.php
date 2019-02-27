<?php
require_once "models/page_model.php";
require_once 'incl/crud.php';

class PageController
{
    /** @var PageModel */
    private $model;

    /** @var CRUD */
    private $crud;

    public function __construct()
    { 
        $this->model = new PageModel();
        $this->crud = new CRUD();
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
                $controller = new UserController($this->model, $this->crud);
                $controller->handleLoginRequest();
                break;
            case 'logout':
                require_once "controllers/user_controller.php";
                $controller = new UserController($this->model, $this->crud);
                $controller->handleLogOutRequest();
                break;
            case 'register':
                require_once "controllers/user_controller.php";
                $controller = new UserController($this->model, $this->crud);
                $controller->handleRegisterRequest();
                break;
            case 'products':
                require_once "controllers/products_controller.php";
                $controller = new ProductsController($this->model, $this->crud);
                $controller->handleProductsRequest();
                break;
            case 'detailProduct':
                require_once "controllers/products_controller.php";
                $controller = new ProductsController($this->model, $this->crud);
                $controller->handleDetailProductsRequest();
                break;
            case 'cart':
                require_once "controllers/products_controller.php";
                $controller = new ProductsController($this->model,$this->crud);
                $controller->handleCartRequest();
                break;
            case 'top5':
                require_once "controllers/products_controller.php";
                $controller = new ProductsController($this->model,$this->crud);
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
