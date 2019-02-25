<?php
define('dbHost',"localhost");
define('dbName',"educom");
define('dbUser',"php_user");
define('dbPassword',"password");

interface iCRUD {
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


 class TestCRUD implements iCRUD {
    public $sqlQueries = array();
    public $bindParams = array();
    public $arrayToReturn = array();
    public $objToReturn = NULL;
 
    #============================================================================
    function createRow($sql, $bindParameters)
    {
       array_push($this->sqlQueries, $sql);
       array_push($this->bindParams, $bindParameters);
       return 2;
    }
 
    #============================================================================
    function readMultiRows($sql, $bindParameters)
    {
       array_push($this->sqlQueries, $sql);
       array_push($this->bindParams, $bindParameters);
       return $this->arrayToReturn;
    }
 
    #============================================================================
    function readOneRow($sql, $bindParameters)   
    {
       array_push($this->sqlQueries, $sql);
       array_push($this->bindParams, $bindParameters);
       return $this->objToReturn;
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