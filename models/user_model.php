<?php
require_once "models/page_model.php";
require_once "incl/session_manager.php";
require_once "incl/crud.php";
require_once "incl/user_crud.php";
require_once "incl/user_repository.php";

/**
* 
* This needs a description
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
    public $errorMessage = '';
    public $passwordNotEqualErr = '';

    // if the form is valid
    public $valid = false;
    // if the user in in the db 
    public $dbValid = false;

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
        if (isset($this->emailErr) or isset($this->passwordErr)) {
            $this->valid = true;
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

        if (empty($this->nameErr) or empty($this->passwordCheckErr) or
            empty($this->emailErr) or empty($this->passwordErr)) {

            // check if provided passwords are the same
            if ($this->password == $this->passwordCheck) {
                $this->valid = true;
            } else {
                $this->passwordNotEqualErr = 'Your passwords are not equal!';
            }
        }
    }

    public function validateUserAgainstDb()
    {
        // TODO: fix me 
        $this->userInfo = $this->findUserByEmail($this->email);

        if ($this->userInfo) {
            return true;
        } else {
            return false;
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

    public function registerUser()
    {
    try {
            if ($this->userRepository->registerUser($this->name,$this->email,$this->password)) {
                $this->dbValid = true;
            } else {
                $this->errorMessage = 'user is already in database';
            }
        } catch (\Throwable $th) {
            $this->errorMessage = $th->getMessage();
        }
    }
}
