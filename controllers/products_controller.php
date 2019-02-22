<?php
require_once "models/products_model.php";
require_once "classes/products_doc.php";
require_once "classes/detail_product_doc.php";

class ProductsController 
{
    
    private $model;

    public function __construct($pageModel)
    {
       $this->model = new ProductsModel($pageModel);
    }

    public function handelProductsRequest() 
    {
        $this->model->getProducts();
        $view = new ProductsDoc($this->model);
        $view->show();
    }

    public function handelDetailProductsRequest()
    {
        $this->model->getProductById();
        $view = new DetailProductDoc($this->model);
        $view->show();

    }


}