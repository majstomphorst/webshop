<?php
require_once "incl/session_manager.php";

class PageModel
{
    public $requested_page;
    public $isPost;
    public $loggedIn;
    public $requested_type;
    public $menuLeft;
    public $menuRight;

    public function __construct(PageModel $model = NULL)
    {
        if ($model) {
            $this->requested_page = $model->requested_page;
            $this->requested_type = $model->requested_type;
            $this->isPost = $model->isPost;
            $this->menuLeft = $model->menuLeft;
            $this->menuRight = $model->menuRight;
            $this->loggedIn = $model->loggedIn;
        }
    }

    public function getRequestedPage()
    {
        $this->requested_type = $_SERVER['REQUEST_METHOD']; /* JH: Wordt dit nog ergens anders gebruikt? */
        $this->isPost = $this->requested_type == 'POST';
        if ($this->isPost) {
            $this->requested_page = getPostVar('page', 'home');
        } else {
            $this->requested_page = getUrlVar('page', 'home');
        }
        $this->generateMenu();
    }

    protected function generateMenu()
    {
        $this->menuLeft = array('home' => 'Home', 'about' => 'About', 'contact' => 'Contact', 'products' => 'Products', 'top5' => 'Top 5');

        if (isLoggedIn()) {
            $this->loggedIn = true;
    
            $this->menuLeft['cart'] = 'ShoppingCart';

            $username = getLoggedInUserName();

            $this->menuRight =  array('logout' => 'Logout ' . ucfirst($username));
        } else {
            $this->loggedIn = false;
            $this->menuRight = array('login' => 'Login', 'register' => 'Register');
        }
    }
}