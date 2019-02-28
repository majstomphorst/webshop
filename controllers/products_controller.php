<?php
require_once "models/products_model.php";
require_once "classes/products_doc.php";
require_once "classes/detail_product_doc.php";
require_once "classes/cart_doc.php";
require_once "classes/top5_doc.php";


class ProductsController
{
    /** @var ProductsModel */
    private $model;

    public function __construct(PageModel $pageModel, CRUD $crud)
    {
        $this->model = new ProductsModel($pageModel,$crud);
    }

    public function handleProductsRequest()
    {
        $this->model->getProducts();
        $view = new ProductsDoc($this->model);
        $view->show();
    }

    public function handleDetailProductsRequest()
    {
        $this->model->getProduct();
        

        if (!$this->model->products) {
            $this->model->requested_page = "products";
            $this->handleProductsRequest();
        } else {
            $view = new DetailProductDoc($this->model);
            $view->show();
        }
    }

    public function handleCartRequest()
    {
        $this->model->handleCartActions();
        $this->model->prepareShoppingCart();
        $view = new CartDoc($this->model);
        $view->show();
    }
    
    public function handleTop5Request()
    {
        $this->model->getTop5();

        $view = new Top5Doc($this->model);
        $view->show();
    } 
}
