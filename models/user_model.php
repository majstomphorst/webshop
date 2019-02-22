<?php
require_once "models/page_model.php";
require_once "incl/data_storage.php";
require_once "incl/session_manager.php";

class UserModel extends PageModel 
{
    public $email = '';
    public $password = '';
    public $emailErr = '';
    public $passwordErr = '';
    public $loginErr = '';
    public $errorMessage = '';
    public $valid = false;
    private $userInfo = null;

    public function __construct(PageModel $model)
    {
        // pass the model on to our parent class (PageModel)
        parent::__construct($model);
    }

    
    public function validateLoginForm()
    {
        $this->email = test_input(getPostVar('email'));
        $this->password = test_input(getPostVar('password'));

        // check if field are empty
        if (empty($this->email)) {$this->emailErr = "Email is required";}
        if (empty($this->password)) {$this->passwordErr = "password is required";}

        if (isset($this->emailErr) or isset($this->passwordErr)) {
            $this->valid = true;
        }
    }

    public function validateUserAgainstDb() 
    {
        $this->userInfo = validateUser($this->email,$this->password);
        if ($this->userInfo) {
            return true;
        } else {
            return false;
        }
    }
    
    public function loginUser() {
        login($this->userInfo);
        $this->generateMenu();
    }
    
    public function logoutUser() {
        logout();
        $this->generateMenu();
    }
}