<?php
require_once "incl/session_manager.php";
require_once "incl/shop_crud.php";
require_once "incl/rating_info.php";
require_once "incl/order_row.php";

class ProductsModel extends PageModel
{
    public $products = array();

    public $productId = null;

    public $cartRows = array();
    public $totalPrice = 0;
    public $jsonData = array();

    public $allowedToBuy = false;

    /** @var ShopCrud */
    public $shopCrud = null;

    public function __construct(PageModel $model, CRUD $crud)
    {
        // pass the model on to our parent class (PageModel)
        parent::__construct($model);
        $this->shopCrud = new ShopCrud($crud);
        $this->allowedToBuy = $this->loggedIn;
    }

    public function getProducts()
    {
        try {
            $this->products = $this->shopCrud->getProducts();
        } catch (\Throwable $th) {
            $data['errorMessage'] = $th->getMessage();
        }
    }

    public function getProduct()
    {
        $this->productId = test_input($this->getUrlVar('id'));
        if (!empty($this->productId)) {
            $this->getProductById($this->productId);
        }
    }

    public function getProductById($productId)
    {
        try {
            $this->products = $this->shopCrud->getProductById($productId);
            return $this->products;
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
            $actionCart = $this->test_input($this->getPostVar('action'));
            $productId = intval($this->test_input($this->getPostVar('productId')));

            switch ($actionCart) {
                case 'addToCart':
                    mutateToCart($productId, 1);
                    break;
                case 'removeFromCart':
                    mutateToCart($productId, -1);
                    break;
                case 'placeOrder':
                    $this->prepareShoppingCart();

                    $cartRows = array();
                    //TODO: replace to CRUD. CRUD could take the object.... for later
                    foreach ($this->cartRows as $orderRow) {
                        array_push($cartRows, $orderRow->convertToArrayForStorage());
                    }

                    $userId = getLoggedInUserId();

                    if ($this->shopCrud->storeOrder($cartRows, $userId)) { 
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
        $this->cartRows = array(); 
        $this->totalPrice = 0;

        foreach (getCart() as $productId => $amount) {
            $product = $this->shopCrud->getProductById($productId);/* JH: Hier wordt voor ieder product in de cart een SQL query gedaan. Het is beter om voor de foreach een $products = getProducts() (= 1 SQL query) te doen om en dan hier te zetten $cartRow = array('product' => $products[$productId]); */
            $orderRow = new OrderRow($product);
            $orderRow->amount = intval($amount);
            $orderRow->linePrice = floatval($orderRow->amount) * floatval($orderRow->unitPrice);
            array_push($this->cartRows,$orderRow);
            $this->totalPrice += $orderRow->amount * $orderRow->unitPrice;
        }

        /* JH: Zet hier $this->optionToBuy = $this->loggedin; */
    }


    public function getTop5()
    {
        $this->products = $this->shopCrud->getTop5Sold();
    }

    public function handleAjaxActions()
    {
        $actionAjax = $this->test_input($this->getPostVar('action'));
        switch ($actionAjax) {
            case 'updateRating':
                $productId = $this->test_input($this->getPostVar('productId')); 
                $rating = $this->test_input($this->getPostVar('rating'));
                $this->shopCrud->updateOrStoreRating($productId, $rating, getLoggedInUserId());
                break;
            case 'getRatingInfo':
                /* JH TIP: Deze 'case' begint aardig lang te worden, misschien private functie van maken? */
                $productIds = $this->getPostVar('productIds'); /* Deze variabele hoeft niet als class variabelen bewaard te blijven, dus kan een lokale variabele zijn */

                foreach ($productIds as $key => $productid) {
                    $productIds[$key] = $this->test_input($productid);
                }

                $userRatings = $this->shopCrud->getUserRating(getLoggedInUserId());
                $avgRatings = $this->shopCrud->getAvgProductRating($productIds);

                $this->prepareRatingInfoForAjax($userRatings,$avgRatings);

                break;
            default:
                # code...
                break;
        }
    }

    public function prepareRatingInfoForAjax($userRatings,$avgRatings) {
        foreach ($avgRatings as $avgRating) {
            $ratingInfo = new RatingInfo($avgRating);
            foreach($userRatings as $userRating) {
                if ($userRating->product_id == $avgRating->product_id) {
                    $ratingInfo->userRating = $userRating->rating;
                }
            }
            array_push($this->jsonData,$ratingInfo);
        }
    }

}
