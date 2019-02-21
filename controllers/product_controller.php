<?php

class ProductController 
{
    
    private $model;

    public function __construct($pageModel)
    {
       $this->model = new PuductModel($pageModel);
    }

    public function handleContactRequest()
    {
        if ($this->model->isPost == "POST") {
            $this->model->validateForm();
        } 
        
        if ($this->model->valid) {
            // show thankYouMessage();
            $view = new ThankYouDoc($this->model);
            $view->show();
        } else {
            $view = new ContactDoc($this->model);
            $view->show();
        }

    }

}