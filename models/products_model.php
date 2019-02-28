<?php
require_once "incl/session_manager.php";
require_once "incl/shop_crud.php";
require_once "incl/rating_info.php";


class ProductsModel extends PageModel
{
    public $products = array();
    /** @var int */
    public $optionToBuy = 'disabled'; /* Dit is iets voor de $view, $optionToBuy zou alleen true of false moeten zijn in een model */
    public $productId = null;

    public $cart = array();
    public $cartRows = array();

    public $jsonData = array();

    /** @var ShopCrud */
    public $shopCrud = null;

    public function __construct(PageModel $model, CRUD $crud)
    {
        // pass the model on to our parent class (PageModel)
        parent::__construct($model);
        $this->shopCrud = new ShopCrud($crud);
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
        $this->productId = test_input(getUrlVar('id'));
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
            $actionCart = test_input(getPostVar('action'));
            $productId = intval(test_input(getPostVar('productId')));

            switch ($actionCart) {
                case 'addToCart':
                    mutateToCart($productId, 1);
                    break;
                case 'removeFromCart':
                    mutateToCart($productId, -1);
                    break;
                case 'placeOrder':
                    
                    $orderInfo = $this->prepareOrderInfoForStorage(); /* JH: Zie opmerking op regel 123 */
                    $userId = getLoggedInUserId();


                    if ($this->shopCrud->storeOrder($orderInfo, $userId)) { /* JH: Dit wordt dan: if (storeOrder($this->cartRows, $userId)) { ... */
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
        /* JH TIP: zet $this->totalPrice = 0; hier */
        foreach (getCart() as $productId => $amount) {
            /* JH TIP: Maak van cartRow een class, dan hoef je hier geen arrays meer te gebruiken */
            $this->cartRow = array('product' => $this->getProductById($productId)); /* JH: Hier wordt voor ieder product in de cart een SQL query gedaan. Het is beter om voor de foreach een $products = getProducts() (= 1 SQL query) te doen om en dan hier te zetten $cartRow = array('product' => $products[$productId]); */
            $this->cartRow['amount'] = intval($amount);
            $this->cartRow['total'] = floatval($this->cartRow['product']->price) * $this->cartRow['amount'];
            array_push($this->cartRows, $this->cartRow);
            /* JH TIP: Bereken ook $this->total_price += $cartRow['total']; */
        }
        /* JH: Zet hier $this->optionToBuy = $this->loggedin; */
    }


    public function getTop5()
    {
        $this->products = $this->shopCrud->getTop5Sold();
    }

    /**
     * collect all the information needed to store the order in the db
     *
     * @return associtive orderInfo array[0 => array['productId','amount','unit_price'],
     *                                    1 => array[],
     *                                    'total_price']
     *
     */
    public function prepareOrderInfoForStorage() /* JH: Als je in $prepareShoppingCart ook de $this->totalPrice berekend, kan deze functie komen te vervallen */
    {
        $this->cart = getCart();  /* JH: Cart hoeft niet in de model bewaard te blijven */
        $orderInfo = array();
        $total_price = 0;

        foreach ($this->cart as $productId => $amount) {

            $productInfo = $this->getProductById($productId); /* JH: Hier wordt voor ieder product in de cart een SQL query gedaan. Het is beter om voor de foreach een $products = getProducts() (= 1 SQL query) te doen om en dan hier te zetten $productInfo => $products[$productId]); */

            $cartRow['productId'] = $productId;
            $cartRow['amount'] = $amount;
            $cartRow['unit_price'] = $productInfo->price;
            $total_price += $amount * $productInfo->price;
            array_push($orderInfo, $cartRow);
        }
        return $orderInfo;
    }

    public function handleAjaxActions()
    {
        $actionAjax = test_input(getPostVar('action')); /* Deze variabele hoeft niet als class variabelen bewaard te blijven, dus kan een lokale variabele zijn */

        switch ($actionAjax) {
            case 'updateRating':
                $productId = test_input(getPostVar('productId')); /* Deze variabele hoeft niet als class variabelen bewaard te blijven, dus kan een lokale variabele zijn */
                $rating = test_input(getPostVar('rating')); /* Deze variabele hoeft niet als class variabelen bewaard te blijven, dus kan een lokale variabele zijn */
                $this->shopCrud->updateOrStoreRating($productId, $rating, getLoggedInUserId());
                break;
            case 'getRatingInfo':
                /* JH TIP: Deze 'case' begint aardig lang te worden, misschien private functie van maken? */
                $productIds = getPostVar('productIds'); /* Deze variabele hoeft niet als class variabelen bewaard te blijven, dus kan een lokale variabele zijn */

                foreach ($productIds as $key => $productid) {
                    $productIds[$key] = test_input($productid);
                }

                $userRatings = $this->shopCrud->getUserRating(getLoggedInUserId()); /* Deze variabele hoeft niet als class variabelen bewaard te blijven, dus kan een lokale variabele zijn */
                $avgRatings = $this->shopCrud->getAvgProductRating($productIds);

                foreach ($avgRatings as $avgRating) {

                    $ratingInfo = new RatingInfo($avgRating);

                    foreach($userRatings as $userRating) {
                        if ($userRating->product_id == $avgRating->product_id) {
                            $ratingInfo->userRating = $userRating->rating;
                        }
                    }
                    array_push($this->jsonData,$ratingInfo);
                }

                break;
            default:
                # code...
                break;
        }
    }

}
