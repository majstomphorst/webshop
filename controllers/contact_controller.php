<?php
require_once "models/contact_model.php";

class ContactController 
{
    // ContactModel
    private $model;

    public function __construct($pageModel)
    {
        // pass the data on to our parent class (basicDoc)
       $this->$model = new ContactModel($pageModel);

    }

    public function handleContactRequest()
    {
        if ($this->model->isPost) {
            $this->model->validateForm();
        }
        $view = new ContactDoc($this->model);
        $view->show();
    }

}