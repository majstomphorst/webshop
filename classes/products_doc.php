<?php
require_once "abstract_product_doc.php";

class ProductsDoc extends ProductDoc
{
    public function __construct($model)
    {
        // pass the data on to our parent class (basicDoc)
        parent::__construct($model);
    }

    protected function startContainer() {
        echo '<div class="container">
        <div class="card-deck">';
    }

    protected function endContainer() {
        echo '</div>
        </div>';
    }

    protected function mainContent()
    {
        if (!isset($this->model->products)){
            echo 'no products found.';
            return;
        }

        // if the user is login activate buy option 
        if ($this->model->loggedIn) {
            $this->model->optionToBuy = '';
        }
        
        $this->startContainer();
        foreach ($this->model->products as $product) {
            $this->showProductCard($product);
        }
        $this->endContainer();

    }

    private function showProductCard($product)
    {
        setlocale(LC_MONETARY, 'nl_NL');
        
        echo '
        <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3 col-offset-0">
            <div class="card border-dark text-center">
                <img src="./assets/images/' . $product['image_name'] . '.png" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">' . $product['name'] . '</h5>
                    <p class="card-text">' . $product['description'] . '</p>
                    <div class="card-footer bg-transparent border-success">' . money_format('%.2n', $product['price']) . '</div>
                    <form action="index.php" method="post">
                        <input type="hidden" name="page" value="cart">
                        <input type="hidden" name="order[action]" value="addToCart">
                        <button value="'.$product['id'].'" type="submit" name="order[productId]" class="btn btn-success btn-block buyButton"'. $this->model->optionToBuy .'>Buy</button>
                    </form>';

                    // check if a user in loggein 
                    if(!$this->model->optionToBuy) {
                        $this->showReatingPanel();
                    }
                    
                echo'  <br>
                    <a href="?page=detailProduct&id='. $product['id'] .'" ><button type="button" class="btn btn-info btn-sm btn-block">More information</button></a>
                </div>
            </div>
        </div>
        ';
    }
}