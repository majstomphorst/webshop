<?php
require_once 'database.php';
require_once '../incl/user_crud.php';

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
     * @return  bool true if user is registerd false oftherwise
     *
     */
    public function registerUser(String $name, String $email, String $password)
    {
        if (!doesUserExist($email, $password)) {
            storeUser($name, $email, $password);
            return true;
        } else {
            return false;
        }
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
        $UserInfo = findUserByEmail($email);

        // if not set no user == false
        if (!isset($UserInfo)) {
            return null;
        }
        if ($UserInfo['password'] == $password) {
            return $UserInfo;
        }
        return null;
    }

/**
 * check if a users email is in the database
 *
 * @param   String email
 * @return  bool true if users emailis in db false otherwise
 *
 */
    public function doesUserExist(String $email)
    {
        $dbUserInformation = findUserByEmail($email);
        if (isset($dbUserInformation)) {
            return true;
        } else {
            return false;
        }
    }

}
