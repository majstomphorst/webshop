<?php
require_once "./models/contact_model.php";
require_once "./classes/contact_doc.php";
require_once "./classes/thank_you_doc.php";

class ContactController 
{
    // PageModel > ContactModel /* JH: TIP use /** @var /ContactModel */ om je editor te laten weten dat $model een Contact Model is */
    private $model;

    public function __construct($pageModel)
    {
       $this->model = new ContactModel($pageModel);
    }

    public function handleContactRequest()
    {
        if ($this->model->isPost == "POST") {
            $this->model->validateForm();
        } 
        
        if ($this->model->valid) {
            $view = new ThankYouDoc($this->model);
            $view->show();
        } else {
            $view = new ContactDoc($this->model);
            $view->show();
        }

    }

}