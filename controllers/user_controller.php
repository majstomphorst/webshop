<?php
require_once "models/user_model.php";
require_once "classes/login_doc.php";
require_once "classes/register_doc.php";
require_once "classes/home_doc.php";

class UserController
{
    /** @var UserModel */
    private $model;

    public function __construct($pageModel,$crud)
    {
        $this->model = new UserModel($pageModel, $crud);
    }

    public function handleLoginRequest()
    {
        $view = new LoginDoc($this->model);

        if ($this->model->isPost) {

            $this->model->validateLoginForm();

            try {
                // check if the login form is complete
                if ($this->model->valid) {

                    if ($this->model->validateUserAgainstDb()) {
                        $this->model->loginUser();
                        
                        $view = new HomeDoc($this->model);
                    } else {
                        $this->model->LoginErr = "Email and password combination invalid";
                    }
                }

            } catch (\Throwable $th) {
                $this->model->errorMessage = $th->getMessage();
            }
        }

        $view->show();
    }

    public function handleRegisterRequest() 
    {
        $view = new RegisterDoc($this->model);

        if ($this->model->isPost) {

            $this->model->validateRegisterForm();

            // check if the register form is complete
            if ($this->model->valid) {

                $this->model->registerUser();

                if ($this->model->dbValid) {
                    // update model 
                    $this->model->requested_page = 'login';
                    $view = new LoginDoc($this->model);

                } // back to register
            }
        }
        $view->show();
    }

    public function handleLogOutRequest() 
    {
        $this->model->logoutUser();
        $view = new HomeDoc($this->model);
        $view->show();
    }

}
