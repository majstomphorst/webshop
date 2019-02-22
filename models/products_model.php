<?php
require_once "incl/database.php";
require_once "incl/session_manager.php";

class ProductsModel extends PageModel
{
    public $products = array();
    public $optionToBuy = 'disabled';
    public $productId = null;

    public $actionCart = null;
    public $cart = array();

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
            $this->productId = test_input(getUrlVar('id'));
            if (!empty($this->productId)) {
                $this->products = getProductById($this->productId);
            } 
        } catch (\Throwable $th) {
            $data['errorMessage'] = $th->getMessage();
        }
    }

        /**
     * mutates the cart
     * 1. adds a new product to the cart with a order amount of 1
     * 2. adds 1 to the already order product
     * 3. subtracks 1 form the already order product if the amount get to 0
     * the product is removed from the cart
     *
     * @return void
     *
     */
    public function handleCartActions()
    {
        if ($this->isPost) {
            // get a dictionary with the order information
            $this->actionCart = test_input(getPostVar('action'));
            $this->productId = test_input(getPostVar('productId'));

            switch ($this->actionCart) {
                case 'addToCart':
                    mutateToCart($this->productId, 1);
                    break;

                case 'removeFromCart':
                    mutateToCart($this->productId, -1);
                    break;
                case 'placeOrder':
                    $orderInfo = prepareOrderInfoForStorage();
                    $userId = getLoggedInUserId();
                    if (storeOrder($orderInfo, $userId)) {
                        removeCart();
                    }
                    break;
            }
        }
    }

    /**
    * reads the database and looks up a user by there email
    *
    * @param   String email to look up in the database
    * @return  associtive array['name','email','password']
    * 
    */
    function prepareShoppingCart()
    {
        $cart = getCart();
        $cartRows = array();

        foreach ($cart as $productId => $amount) {
            $cartRow = array('product' => getProductById($productId));
            $cartRow['amount'] = intval($amount);
            $cartRow['total'] = floatval($cartRow['product']['price']) * $cartRow['amount'];
            array_push($cartRows ,$cartRow);
        }
        $this->cart = array('cart' => $cartRows);
    }

}