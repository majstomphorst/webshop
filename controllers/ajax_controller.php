<?php
require_once "models/products_model.php";
require_once "classes/ajax_doc.php";

class AjaxController 
{
    /** @var ProductsModel */
    private $model;

    public function __construct(PageModel $model, CRUD $crud)
    {
        $this->model = new ProductsModel($model,$crud);
    }

    public function handleAjaxRequest() 
    {
        $this->model->handleAjaxActions();
        $view = new AjaxDoc($this->model);
        $view->show();
    }
}