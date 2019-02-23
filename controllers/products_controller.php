<?php
require_once "models/products_model.php";
require_once "classes/products_doc.php";
require_once "classes/detail_product_doc.php";
require_once "classes/cart_doc.php";
require_once "classes/top5_doc.php";


class ProductsController
{
    private $model;

    public function __construct(PageModel $pageModel)
    {
        $this->model = new ProductsModel($pageModel);
    }

    public function handleProductsRequest()
    {
        $this->model->getProducts();
        $view = new ProductsDoc($this->model);
        $view->show();
    }

    public function handleDetailProductsRequest()
    {
        $this->model->getProductById();
        $view = new DetailProductDoc($this->model);
        if (count($this->model->products) <= 0) {

            $this->model->requested_page = "products";
            $this->handelProductsRequest();
        } else {
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
