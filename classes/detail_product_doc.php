<?php
require_once "abstract_product_doc.php";

class DetailProductDoc extends productDoc
{
    public function __construct($mydata)
    {
        // pass the data on to our parent class (basicDoc)
        parent::__construct($mydata);
    }

    public function mainContent()
    {
        $product = array();
        if (!empty($this->data['product'])) {
            $product = $this->data['product'];
        }

        $optionToBuy = '';
        if (!$this->data['loggedIn']) {
            $optionToBuy = 'disabled ';
        }

        echo '
                <div class="row">
                    <div class="col-4">
                        <img src="./assets/images/' . $product['image_name'] . '.png" class="img-fluid" alt="Responsive image">
                    </div>
                    <div class="col-8">
                        <div class="card">
                            <div class="card-header">
                                <h2>' . $product['name'] . ' <span class="badge badge-secondary">Exclusive</span></h2>
                            </div>
                            <div class="card-body">
                                <h5 class="card-textl">' . $product['description'] . '</h5>
                                <hr>
                                <h3>&#8364; ' . money_format('%.2n', $product['price']) . '</h3>
                                <hr>';
        if ($this->data['loggedIn']) {
            $this->showReatingPanel();
        }

        echo '<form action="index.php" method="post">
                                    <input type="hidden" name="page" value="cart">
                                    <input type="hidden" name="order[action]" value="addToCart">
                                    <button value="'.$product['id'].'" type="submit" name="order[productId]" class="btn btn-success btn-block buyButton"'. $optionToBuy .'>Buy</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    </div>';
    }

}
