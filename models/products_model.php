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
    private $cartRows = array();

    private $actionAjax = '';
    private $productIds = array();
    private $userRatings = array();

    public $jsonData = array();

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
                    $orderInfo = $this->prepareOrderInfoForStorage();
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
    public function prepareShoppingCart()
    {
        $this->cart = getCart();
        $this->cartRows = array();

        foreach ($this->cart as $productId => $amount) {
            $cartRow = array('product' => getProductById($productId));
            $cartRow['amount'] = intval($amount);
            $cartRow['total'] = floatval($cartRow['product']['price']) * $cartRow['amount'];
            array_push($this->cartRows, $cartRow);
        }
        $this->cart = array('cart' => $this->cartRows);
    }

    public function getTop5()
    {
        $this->products = getTop5Sold();
    }

    /**
     * collect all the information needed to store the order in the db
     *
     * @return associtive orderInfo array[0 => array['productId','amount','unit_price'],
     *                                    1 => array[],
     *                                    'total_price']
     *
     */
    public function prepareOrderInfoForStorage()
    {
        $this->cart = getCart();
        $orderInfo = array();
        $total_price = 0;

        foreach ($this->cart as $productId => $amount) {
            $productInfo = getProductById($productId);

            $cartRow['productId'] = $productId;
            $cartRow['amount'] = $amount;
            $cartRow['unit_price'] = $productInfo['price'];
            $total_price += $amount * $productInfo['price'];

            array_push($orderInfo, $cartRow);
        }
        $orderInfo['total_price'] = $total_price;
        return $orderInfo;
    }

    public function handleAjaxActions()
    {
        $this->actionAjax = test_input(getPostVar('action'));

        switch ($this->actionAjax) {
            case 'updateRating':
                $this->productId = test_input(getPostVar('productId'));
                $this->rating = test_input(getPostVar('rating'));
                updateOrStoreRating($this->productId, $this->rating, getLoggedInUserId());

                break;
            case 'getRatingInfo':

                $this->productIds = getPostVar('productIds');

                foreach ($this->productIds as $key => $productid) {
                    $this->productIds[$key] = test_input($productid);
                }
        
                $this->userRatings = getUserRating($this->productIds, getLoggedInUserId());
                $this->jsonData = getAvgProductRating($this->productIds);

                // create the correct data structure
                foreach ($this->jsonData as $index => $productInfo) {
                    $key = $productInfo['product_id'];
                    if (array_key_exists($key, $this->userRatings)) {
                        $this->jsonData[$index]['userRating'] = $this->userRatings[$key];
                    }
                };

                break;
            default:
                # code...
                break;
        }
    }

}
