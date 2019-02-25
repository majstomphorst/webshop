<?php 
require_once "classes/response_doc.php";
require_once "models/products_model.php";

class AjaxDoc extends ResponseDoc
{
    private $model;

    public function __construct(ProductsModel $model)
    {
        $this->model = $model;
    }

    public function show() 
    {   
        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($this->model->jsonData);
    }
}
