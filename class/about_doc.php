<?php
require_once 'basic_doc.php';

class aboutDoc extends basicDoc
{
    public function __construct($mydata)
    {
        // pass the data on to our parent class (basicDoc)
        parent::__construct($mydata);
    }

    // Override function from basicDoc
    protected function mainContent()
    {
        echo "<p>This is my about Page</p>";
    }
}