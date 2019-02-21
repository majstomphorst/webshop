
<?php

require_once "abstract_form_doc.php";

class RegisterDoc extends FormDoc
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
        $this->formField("text","name","Your email...",
                            getArrayVar($this->data, 'name'),
                            getArrayVar($this->data, 'nameErr'));
        $this->formField("email","email","Your email...",
                            getArrayVar($this->data, 'email'),
                            getArrayVar($this->data, 'emailErr'));
        $this->formField("password","password","Your password...",
                            getArrayVar($this->data, 'password'),
                            getArrayVar($this->data, 'passwordErr'));
        $this->formField("password","passwordCheck","Your password again...",
                            getArrayVar($this->data, 'passwordCheck'),
                            getArrayVar($this->data, 'passwordCheckErr'));
        $this->formErrorLine(getArrayVar($this->data, 'passwordNotEqualErr'));
        $this->hiddenFormField();
        $this->formButton("submit");
        
    }

}
?>