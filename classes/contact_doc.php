<?php
require_once "abstract_form_doc.php";

class ContactDoc extends FormDoc
{
    public function __construct($model)
    {
        // pass the data on to our parent class (basicDoc)
        parent::__construct($model);
    }
    
    protected function mainContent() 
    {
        // $this->model->$requested_page
        $this->formTitle($this->model->requested_page);
        $this->startForm();

        $this->formField("text","name","Your name...",
                            $this->model->name, 
                            $this->model->nameErr);
        $this->formField("email","email","Your email...",
                            $this->model->email, 
                            $this->model->emailErr);
        $this->formField("","text","Your text...",
                            $this->model->text,
                            $this->model->textErr);

        $this->hiddenFormField();

        $this->formButton("submit");
    }
}

?>