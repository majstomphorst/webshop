<?php
require_once "models/products_model.php";
require_once "classes/ajax_doc.php";

class AjaxController 
{
    private $model;

    public function __construct(PageModel $model)
    {
        $this->model = new ProductsModel($model);
    }

    public function handleAjaxRequest() 
    {
        $this->model->handleAjaxActions();
        $view = new AjaxDoc($this->model);
        $view->show();
    }
}