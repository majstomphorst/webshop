<?php
require_once "models/page_model.php";
require_once "incl/session_manager.php";
require_once "incl/crud.php";
require_once "incl/user_crud.php";
require_once "incl/user_repository.php";

/**
* 
* This needs a description /* JH: IT SURE DOES
* 
*
* 
*/
class UserModel extends PageModel
{
    public $name = '';
    public $email = '';
    public $password = '';
    public $passwordCheck = '';

    public $nameErr = '';
    public $emailErr = '';
    public $passwordErr = '';
    public $passwordCheckErr = '';
    public $loginErr = '';
    public $registerErr = '';
    public $passwordNotEqualErr = '';

    // if the user in in the db 
    public $dbValid = false;
    public $formValid = false;

    private $userInfo = null;

    /** @var UserRepository */
    private $userRepository = null;

    public function __construct(PageModel $model, CRUD $crud)
    {
        // pass the model on to our parent class (PageModel)
        parent::__construct($model);
        $this->userRepository = new UserRepository(new UserCrud($crud));
    }

    public function validateLoginForm()
    {
        // collecting data from loginForm
        $this->email = test_input(getPostVar('email'));
        $this->password = test_input(getPostVar('password'));

        // check if field are empty
        if (empty($this->email)) {$this->emailErr = "Email is required";}
        if (empty($this->password)) {$this->passwordErr = "password is required";}

        // if the fields are not empty
        if (!empty($this->email) and !empty($this->password) ){
            $this->formValid = true;
        }
    }

    public function validateRegisterForm()
    {
        // collecting data from registerForm
        $this->name = test_input(getPostVar('name'));
        $this->email = test_input(getPostVar('email'));
        $this->password = test_input(getPostVar('password'));
        $this->passwordCheck = test_input(getPostVar('passwordCheck'));

        // check if field are empty
        if (empty($this->name)) {$this->nameErr = "Name is required";}
        if (empty($this->email)) {$this->emailErr = "Email is required";}
        if (empty($this->password)) {$this->passwordErr = "password is required";}
        if (empty($this->passwordCheck)) {$this->passwordCheckErr = "password is required";}

        
        if (!empty($this->name) and !empty($this->email) and
            !empty($this->password) and !empty($this->passwordCheck)) {

            // check if provided passwords are the same
            if ($this->password == $this->passwordCheck) {
                $this->formValid = true;
            } else {
                $this->passwordNotEqualErr = 'Your passwords are not equal!';
            }
        }
    }

    public function validateUserAgainstDb()
    {
        $this->userInfo = $this->userRepository->doesUserExist($this->email);
        if ($this->userInfo) {
            $this->dbValid = true;
        } else {
            $this->loginErr = "Email and password combination invalid";
            $this->dbValid = false;
        }
    }

    public function loginUser()
    {
        login($this->userInfo);
        $this->generateMenu();
    }

    public function logoutUser()
    {
        logout();
        $this->generateMenu();
    }

    public function registerUserInDb()
    {
        $this->userRepository->registerUser($this->name,$this->email,$this->password);
    }

    public function allowToRegisterUser()
    {
        if($this->userRepository->doesUserExist($this->email)){
            $this->registerErr = 'user is already in database';
            $this->dbValid = false;
        } else {
            $this->dbValid = true;
        }
    }

}
