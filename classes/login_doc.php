<?php

require_once "abstract_form_doc.php";

class LoginDoc extends FormDoc
{


    public function __construct($mydata)
    {
        // pass the data on to our parent class (basicDoc)
        parent::__construct($mydata);

    }


    protected function mainContent() 
    {
        $this->formTitle($this->data['page']);
        $this->startForm();

        $this->formField("email","email","Your email...",
                            getArrayVar($this->data, 'email'),
                            getArrayVar($this->data, 'emailErr'));
        $this->formField("password","password","Your password...",
                            getArrayVar($this->data, 'password'),
                            getArrayVar($this->data, 'passwordErr'));
        $this->hiddenFormField();
        $this->formButton("submit");
    }

}
?>