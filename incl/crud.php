<?php
define('dbHost', "localhost");
define('dbName', "educom");
define('dbUser', "php_user");
define('dbPassword', "password");

interface iCRUD
{
    #============================================================================
    # Insert a row of values into from the database
    #
    # @param string $sql the SQL string with for example ':email' for parameters
    # @param array  $bindParameters associative array ( 'email' => 'joe@a.b' );
    #
    # @return int the inserted id or 0 if failed
    #============================================================================
   function createRow($sql, $bindParameters);

    #============================================================================
    # Read an array of objects from the database
    #
    # @param string $sql the SQL string with for example ':email' for parameters
    # @param array  $bindParameters associative array ( 'email' => 'joe@a.b' );
    #
    # @return array (associative) array of objects or an empty array
    #============================================================================
    function readMultiRows($sql, $bindParameters);

    #============================================================================
    # Read one object from the database
    #
    # @param string $sql the SQL string with for example ':email' for parameters
    # @param array  $bindParameters associative array ( 'email' => 'joe@a.b' );
    #
    # @return object the object found or NULL otherwise
    #============================================================================
    function readOneRow($sql, $bindParameters);

    #============================================================================
    # Update values into from the database
    #
    # @param string $sql the SQL string with for example ':email' for parameters
    # @param array  $bindParameters associative array ( 'email' => 'joe@a.b' );
    #
    # @return int number of updated rows or 0 if failed
    #============================================================================
    function updateRow($sql, $bindParameters);

    #============================================================================
    # Removes rows from the database
    #
    # @param string $sql the SQL string with for example ':email' for parameters
    # @param array  $bindParameters associative array ( 'email' => 'joe@a.b' );
    #
    # @return int number of deleted rows or 0 if failed
    #============================================================================
    function deleteRows($sql, $bindParameters);
}

class CRUD
{
   private $conn = null;

   function __construct()
   {
       $this->createConnection();
   }

   private function createConnection()
   {
      if (!$this->conn) {
         try {
            $conn = new PDO("mysql:host=" . dbHost . ";dbname=" . dbName . "", dbUser, dbPassword);

            // set the PDO error mode to exception and default obj
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

            } catch (PDOException $e) {

               echo "Error: " . $e->getMessage();

            }
            $this->conn = $conn;
      }
   }

    #============================================================================   
    public function createRow(String $sql, Array $bindParameters)
    {
        $stmt = $this->conn->prepare($sql);
        
        foreach ($bindParameters as $key => $value) {
            $stmt->bindValue(':'.$key, $value);
        }
        
        $stmt->execute();
        $lastID = $this->conn->lastInsertId();
        return $lastID;
    }

    #============================================================================
    function readMultiRows($sql, $bindParameters = null)
    {
        $stmt = $this->conn->prepare($sql);
        if ($bindParameters) {
            foreach ($bindParameters as $key => $value) {
                $stmt->bindValue(':'.$key, $value);
            }
        }

        $stmt->execute();

        $result = $stmt->fetchall();

        return $result;
    }

    #============================================================================
    function readOneRow($sql, $bindParameters)
    {
        $stmt = $this->conn->prepare($sql);
        foreach ($bindParameters as $key => $value) {
            $stmt->bindValue(':'.$key, $value);
        }

        $stmt->execute();

        $result = $stmt->fetch();

        return $result;
    }

    #============================================================================
    function updateRow($sql, $bindParameters)
    {
        array_push($this->sqlQueries, $sql);
        array_push($this->bindParams, $bindParameters);
        return 1;
    }

    #============================================================================
    function deleteRows($sql, $bindParameters)
    {
        array_push($this->sqlQueries, $sql);
        array_push($this->bindParams, $bindParameters);
        return 1;
    }

}
