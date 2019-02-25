<?php
require_once "models/products_model.php";

class AjaxController {
    private $model;

    public function __construct(PageModel $model)
    {
        $this->model = new ProductsModel($model);
    }

    public function handleAjaxRequest() 
    {
        $this->model->handleAjaxActions();
    }
}