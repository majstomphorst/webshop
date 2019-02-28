<?php
require_once "incl/session_manager.php";
require_once "incl/shop_crud.php";


class ProductsModel extends PageModel
{
    public $products = array();
    /** @var int */
    public $optionToBuy = 'disabled'; /* Dit is iets voor de $view, $optionToBuy zou alleen true of false moeten zijn in een model */
    public $productId = null;

    public $cart = array();
    private $cartRows = array();

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

    public function getProductById()
    {
        try {
            $this->productId = test_input(getUrlVar('id'));
            if (!empty($this->productId)) {
                $this->products = $this->shopCrud->getProductById($this->productId);
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
                    if (storeOrder($orderInfo, $userId)) { /* JH: Dit wordt dan: if (storeOrder($this->cartRows, $userId)) { ... */
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
            $cartRow = array('product' => getProductById($productId)); /* JH: Hier wordt voor ieder product in de cart een SQL query gedaan. Het is beter om voor de foreach een $products = getProducts() (= 1 SQL query) te doen om en dan hier te zetten $cartRow = array('product' => $products[$productId]); */
            $cartRow['amount'] = intval($amount);
            $cartRow['total'] = floatval($cartRow['product']['price']) * $cartRow['amount'];
            array_push($this->cartRows, $cartRow);
            /* JH TIP: Bereken ook $this->total_price += $cartRow['total']; */
        }
        $this->cart = array('cart' => $this->cartRows); /* JH: Array 'cart' is niet nodig, als je in de model/view ook werkt met $this->cartRows; */
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
            $productInfo = getProductById($productId); /* JH: Hier wordt voor ieder product in de cart een SQL query gedaan. Het is beter om voor de foreach een $products = getProducts() (= 1 SQL query) te doen om en dan hier te zetten $productInfo => $products[$productId]); */

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
        $actionAjax = test_input(getPostVar('action')); /* Deze variabele hoeft niet als class variabelen bewaard te blijven, dus kan een lokale variabele zijn */

        switch ($actionAjax) {
            case 'updateRating':
                $this->productId = test_input(getPostVar('productId')); /* Deze variabele hoeft niet als class variabelen bewaard te blijven, dus kan een lokale variabele zijn */
                $this->rating = test_input(getPostVar('rating')); /* Deze variabele hoeft niet als class variabelen bewaard te blijven, dus kan een lokale variabele zijn */
                updateOrStoreRating($this->productId, $this->rating, getLoggedInUserId());

                break;
            case 'getRatingInfo':
                /* JH TIP: Deze 'case' begint aardig lang te worden, misschien private functie van maken? */
                $productIds = getPostVar('productIds'); /* Deze variabele hoeft niet als class variabelen bewaard te blijven, dus kan een lokale variabele zijn */

                foreach ($productIds as $key => $productid) {
                    $productIds[$key] = test_input($productid);
                }

                $userRatings = $this->shopCrud->getUserRating(getLoggedInUserId()); /* Deze variabele hoeft niet als class variabelen bewaard te blijven, dus kan een lokale variabele zijn */
                $avgRatings = $this->shopCrud->getAvgProductRating($productIds);

                $json = array();
                foreach ($avgRatings as $key => $avgRating) {
                    $avgRating->product_id;
                    
                    foreach($userRatings as $key2 => $userRating) {
                        if ($userRating->product_id == $avgRating->product_id) {
                            $json[$userRating->product_id] = array('youRating'=> $userRating->rating,'avgRating' => $avgRating->avgRating);
                        }
                    }
                }
                $this->jsonData = $json;
                // create the correct data structure
                // foreach ($this->jsonData as $index => $productInfo) {
                //     $key = $productInfo['product_id'];
                //     if (array_key_exists($key, $this->userRatings)) {
                //         $this->jsonData[$index]['userRating'] = $this->userRatings[$key];
                //     } 

                break;
            default:
                # code...
                break;
        }
    }

}
