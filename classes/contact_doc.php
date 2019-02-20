<?php
require_once "abstracte_form_doc.php";

class ContactDoc extends FormDoc
{

    public function __construct($mydata)
    {
        // pass the data on to our parent class (basicDoc)
        parent::__construct($mydata);
    }


    protected function mainContent() 
    {
        $this->startForm();
        $this->formField("text","name","Your email...",getArrayVar($this->data, 'email'));
        $this->formButton("submit");
    }    
}

?>