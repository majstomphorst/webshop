<?php 

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
    public function createRow(String $sql, array $bindParameters);

    #============================================================================
    # Read an array of objects from the database
    #
    # @param string $sql the SQL string with for example ':email' for parameters
    # @param array  $bindParameters associative array ( 'email' => 'joe@a.b' );
    #
    # @return array (associative) array of objects or an empty array
    #============================================================================
    function readMultiRows(String $sql, array $bindParameters);

    #============================================================================
    # Read one object from the database
    #
    # @param string $sql the SQL string with for example ':email' for parameters
    # @param array  $bindParameters associative array ( 'email' => 'joe@a.b' );
    #
    # @return object the object found or NULL otherwise
    #============================================================================
    function readOneRow(String $sql, array $bindParameters);

    #============================================================================
    # Update values into from the database
    #
    # @param string $sql the SQL string with for example ':email' for parameters
    # @param array  $bindParameters associative array ( 'email' => 'joe@a.b' );
    #
    # @return int number of updated rows or 0 if failed
    #============================================================================
    function updateRow(String $sql, array $bindParameters);

    #============================================================================
    # Removes rows from the database
    #
    # @param string $sql the SQL string with for example ':email' for parameters
    # @param array  $bindParameters associative array ( 'email' => 'joe@a.b' );
    #
    # @return int number of deleted rows or 0 if failed
    #============================================================================
    function deleteRows(String $sql, array $bindParameters);
}