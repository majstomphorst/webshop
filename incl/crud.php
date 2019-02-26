<?php
define('dbHost', "localhost");
define('dbName', "educom");
define('dbUser', "php_user");
define('dbPassword', "password");

require_once 'icrud.php';

/**
 * generic class for database connections
 *
 * it handles the connection and it can
 * Create, Retrive, Update, Delete
 */
class CRUD implements iCRUD
{

    /** @var PDO */
    private $conn = null;

    public function __construct()
    {
        $this->createConnection();
    }

    /**
     * setup and configures the database with PDO
     */
    private function createConnection()
    {
        if (!$this->conn) {
            $conn = new PDO("mysql:host=" . dbHost . ";dbname=" . dbName . "", dbUser, dbPassword, []);
            // set the PDO error mode to exception and default obj
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $this->conn = $conn;
        }
    }

    /**
     *
     * @param string $sql the SQL string with for example ':email' for parameters
     * @param array  $bindParameters associative array ( 'email' => 'joe@a.b' );
     * or associative array ( 'somekey' => array('0' => '1'), email' => 'joe@a.b' )
     *
     * @return void
     * @throws
     */
    private function prepareAndExecute($sql, $bindParameters)
    {
        foreach ($bindParameters as $key => $value) {
            if (is_array($value)) {
                $partialStr = '';
                foreach ($value as $index => $id) {

                    if (!empty($partialStr)) {
                        $partialStr .= ', ';
                    }

                    $partialStr .= ':' . $key . $index;
                    $bindParameters[$key . $index] = $id;
                }
                // replace the placeholder with the new genarated placeholder
                $sql = str_replace(':' . $key, $partialStr, $sql);
                // cleans the array for use
                unset($bindParameters[$key]);
            }
        }

        $stmt = $this->conn->prepare($sql);

        // bind the keys and the values in the $sql statment
        foreach ($bindParameters as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }

        $stmt->execute();

        return $stmt;
    }

    /**
     * Insert a row of values into from the database
     * @param string $sql the SQL string with for example ':email' for parameters
     * @param array  $bindParameters associative array ( 'email' => 'joe@a.b' );
     *
     * @return int the inserted id or 0 if failed
     */
    public function createRow(String $sql, array $bindParameters)
    {
        $this->prepareAndExecute($sql, $bindParameters);
        $lastID = $this->conn->lastInsertId();
        return $lastID;
    }

    /**
     * Read an array of objects from the database
     *
     * @param string $sql the SQL string with for example ':email' for parameters
     * @param array  $bindParameters associative array ( 'email' => 'joe@a.b' );
     *
     * @return array (associative) array of objects or an empty array
     */
    public function readMultiRows($sql, $bindParameters = array())
    {
        $stmt = $this->prepareAndExecute($sql, $bindParameters);
        $result = $stmt->fetchall();
        return $result;
    }

    /**
     * Read one object from the database
     *
     * @param string $sql the SQL string with for example ':email' for parameters
     * @param array  $bindParameters associative array ( 'email' => 'joe@a.b' );
     *
     * @return object the object found or NULL otherwise
     */
    public function readOneRow($sql, $bindParameters)
    {
        $stmt = $this->prepareAndExecute($sql, $bindParameters);
        $result = $stmt->fetch();
        return $result;
    }

    /**
     * Update values into from the database
     *
     * @param string $sql the SQL string with for example ':email' for parameters
     * @param array  $bindParameters associative array ( 'email' => 'joe@a.b' );
     *
     * @return int number of updated rows or 0 if failed
     */
    public function updateRow($sql, $bindParameters)
    {
        $stmt = $this->prepareAndExecute($sql, $bindParameters);

        return $stmt->rowCount();
    }

    /**
     * Removes rows from the database
     *
     * @param string $sql the SQL string with for example ':email' for parameters
     * @param array  $bindParameters associative array ( 'email' => 'joe@a.b' );
     *
     * @return int number of deleted rows or 0 if failed
     */
    public function deleteRows($sql, $bindParameters)
    {
        $stmt = $this->prepareAndExecute($sql, $bindParameters);
        return $stmt->rowCount();
    }
}
