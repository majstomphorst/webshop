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
        try {
            $this->products = getProducts();
        } catch (\Throwable $th) {
            $data['errorMessage'] = $th->getMessage();
        }
    }

    public function getProductById()
    {
        try {
            $this->productId = getUrlVar('id');
            if (!empty($this->productId)) {
                $this->products = getProductById($this->productId);
            }
        } catch (\Throwable $th) {
            $data['errorMessage'] = $th->getMessage();
        }

    }

}