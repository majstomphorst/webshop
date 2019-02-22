
<?php

require_once "abstract_form_doc.php";

class RegisterDoc extends FormDoc
{

    public function __construct($model)
    {
        // pass the data on to our parent class (basicDoc)
        parent::__construct($model);
    }


    protected function mainContent() 
    {
        $this->formTitle($this->model->requested_page);
        $this->startForm();
        $this->formField("text","name","Your name",
                            $this->model->name,
                            $this->model->nameErr);
        $this->formField("email","email","Your email...",
                            $this->model->email,
                            $this->model->emailErr);
        $this->formField("password","password","Your password...",
                            $this->model->password,
                            $this->model->passwordErr);
        $this->formField("password","passwordCheck","Your password again...",
                            $this->model->passwordCheck,
                            $this->model->passwordCheckErr);
        $this->formErrorLine($this->model->passwordNotEqualErr);
        $this->hiddenFormField();
        $this->formButton("submit");
        
    }

}
?>