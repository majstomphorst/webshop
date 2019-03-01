<?php
require_once 'user_crud.php';

class UserRepository
{
    /** @var UserCrud */
    private $userCrud = null;

    public function __construct(UserCrud $userCrud)
    {
        $this->userCrud = $userCrud;
    }

    /**
     * registers a user in the databse if the email and password is uniek
     *
     * @param   String name
     * @param   String email
     * @param   String password
     *
     */
    public function registerUser(String $name, String $email, String $password)
    {
        $this->userCrud->storeUser($name, $email, $password);
    }

    /**
     * check if the provided credentials are in the database
     *
     * @param   String email
     * @param   String password
     * @return  bool true if user user credentials correct false otherwise
     *
     */
    public function validateUser(String $email, String $password)
    {
        $userInfo = $this->userCrud->findUserByEmail($email);

        // if not set no user == false
        if (!isset($userInfo)) {
            return false;
        }
        if (password_verify($password, $userInfo->password)) {
            return $userInfo;
        } else {
            return false;
        }

    }

    /**
     * check if a users email is in the database
     *
     * @param   String email
     * @return  UserInfo or fales
     *
     */
    public function doesUserExist(String $email)
    {
        $dbUserInformation = $this->userCrud->findUserByEmail($email);
        if (!empty($dbUserInformation)) {
            return $dbUserInformation;
        } else {
            return false;
        }
    }
}
