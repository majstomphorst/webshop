<?php
require_once "models/user_model.php";
require_once "classes/login_doc.php";
require_once "classes/register_doc.php";
require_once "classes/home_doc.php";

class UserController
{
    /** @var UserModel */
    private $model;

    public function __construct($pageModel, $crud)
    {
        $this->model = new UserModel($pageModel, $crud);
    }

    public function handleLoginRequest()
    {
        $view = new LoginDoc($this->model);

        if ($this->model->isPost) {

            // check if the loginform is complete
            $this->model->validateLoginForm();

            if ($this->model->formValid) {
                // check if credentials are in the db
                try {
                    $this->model->validateUserAgainstDb();
                    
                    $this->model->hashPassword();

                    if ($this->model->dbValid) {
                        $this->model->loginUser();
                        $view = new HomeDoc($this->model);
                    } else {
                        $this->model->password = '';
                    }

                } catch (\Throwable $th) {
                    $this->model->errorMessage = $th->getMessage();
                }
            }
        }
        $view->show();
    }

    public function handleRegisterRequest()
    {
        $view = new RegisterDoc($this->model);

        if ($this->model->isPost) {

            $this->model->validateRegisterForm();

            if ($this->model->formValid) {
                try {
                    $this->model->allowToRegisterUser();
                
                    if ($this->model->dbValid) {

                        $this->model->hashPassword();
                        // register user
                        $this->model->registerUserInDb();
                        // update model 
                        $this->model->prepareForLoginView();

                        $view = new LoginDoc($this->model);
    
                    } // back to register
                } catch (\Throwable $th) {
                    $this->model->errorMessage = $th->getMessage();
                }
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
