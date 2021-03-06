<?php

require_once "abstract_form_doc.php";
require_once "models/user_model.php";

class LoginDoc extends FormDoc
{
    public function __construct(PageModel $model)
    {
        // pass the data on to our parent class (basicDoc)
        parent::__construct($model);
    }

    protected function mainContent() 
    {
        $this->formTitle($this->model->requested_page);
        $this->formErrorLine($this->model->loginErr);
        $this->startForm();

        $this->formField("email","email","Your email...",
                            $this->model->email,
                            $this->model->emailErr);
        $this->formField("password","password","Your password...",
                            $this->model->password,
                            $this->model->passwordErr);
        $this->addHiddenFieldWithPageName();
        $this->formButton("submit");
    }

}
?>