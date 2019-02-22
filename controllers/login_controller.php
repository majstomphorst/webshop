<?php
require_once "models/user_model.php";
require_once "classes/login_doc.php";
require_once "classes/home_doc.php";

class LoginController
{
    private $model;

    public function __construct($pageModel)
    {
        $this->model = new UserModel($pageModel);
    }

    public function handelLoginRequest()
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

}
