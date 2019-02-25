<?php
require_once "models/user_model.php";
require_once "classes/login_doc.php";
require_once "classes/register_doc.php";
require_once "classes/home_doc.php";

class UserController
{
    private $model;

    public function __construct($pageModel)
    {
        $this->model = new UserModel($pageModel);
    }

    public function handleLoginRequest()
    {
        $view = new LoginDoc($this->model);

        if ($this->model->isPost) {

            $this->model->validateLoginForm();

            /* JH TIP: ik zou de try { } catch  verplaatsen naar validateUserAgainstDb */
            try {
                // check if the login form is complete
                if ($this->model->valid) {

                    /* JH: Ik zou dit opschrijven als 
                    
                           $this->model->validateUserAgainstDb();
                           if ($this->model->dbValid) { 
                              $this->model->loginUser(); ...
                     */
                    if ($this->model->validateUserAgainstDb()) {
                        $this->model->loginUser();
                        
                        $view = new HomeDoc($this->model);
                    } else {
                        /* JH: Deze actie mag je in validateUserAgainstDb() doen. */
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

                $this->model->registerUser(); /* JH: De naam suggereerd dat het registeren altijd lukt, misschien een naam als tryToRegisterUserInDb() ? */

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
