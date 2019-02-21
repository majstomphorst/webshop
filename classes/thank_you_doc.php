<?php 
require_once 'basic_doc.php';

/**
 * undocumented class
 */
class ThankYouDoc extends basicDoc
{
    public function __construct($model)
    {
        // pass the data on to our parent class (basicDoc)
        parent::__construct($model);
    }

    protected function mainContent()
    {
        echo "<div class='alert alert-success' role='alert'>
        <strong>Aww yeah,</strong> you successfully filled out the form you're AWSOME! <br>
        Your name is: ". $this->model->name . "<br>
        Your email is: ". $this->model->email ."<br>
        Your text is: ". $this->model->text . "
        </div>";   
    }


}