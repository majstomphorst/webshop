<?php
require_once "incl/database.php";

class ProductsModel extends PageModel
{
    public $products = array();
    public $optionToBuy = 'disabled';
    public $productId = null;

    public function __construct(PageModel $model)
    {
        // pass the model on to our parent class (PageModel)
        parent::__construct($model);
    }

    public function getProducts() 
    {
        $this->products = getProducts();
    }

    public function getProductById()
    {
        $this->productId = getUrlVar('id');
        if ($this->productId) {
            $this->products = getProductById($this->productId );
        }
    }
}