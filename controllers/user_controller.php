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

            // check if the loginform is complete 
            $this->model->validateLoginForm();
            // check if credentials are in the db
            try {
                $this->model->validateUserAgainstDb();
            } catch (\Throwable $th) {
                $this->model->errorMessage = $th->getMessage();
            }
            
            if ($this->model->dbValid) {
                $this->model->loginUser();
                $view = new HomeDoc($this->model);
            } else {

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

                $this->model->registerUser(); /** JH: De naam suggereerd dat het registeren altijd lukt, misschien een naam als tryToRegisterUserInDb() ? */

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
