<?php
require_once "abstract_product_doc.php";

class DetailProductDoc extends productDoc
{
    public function __construct($model)
    {
        // pass the data on to our parent class (basicDoc)
        parent::__construct($model);
    }

    public function mainContent()
    {
        if (!isset($this->model->products)){
            echo 'no products found.';
            return;
        }

        // if the user is login activate buy option 
        if ($this->model->loggedIn) {
            $this->model->optionToBuy = '';
        }

        echo '
                <div class="row">
                    <div class="col-4">
                        <img src="./assets/images/' . $this->model->products['image_name'] . '.png" class="img-fluid" alt="Responsive image">
                    </div>
                    <div class="col-8">
                        <div class="card">
                            <div class="card-header">
                                <h2>' . $this->model->products['name'] . ' <span class="badge badge-secondary">Exclusive</span></h2>
                            </div>
                            <div class="card-body">
                                <h5 class="card-textl">' . $this->model->products['description'] . '</h5>
                                <hr>
                                <h3>&#8364; ' . money_format('%.2n', $this->model->products['price']) . '</h3>
                                <hr>';
        if ($this->model->loggedIn) {
            $this->showReatingPanel();
        }

        echo '<form action="index.php" method="post">
                                    <input type="hidden" name="page" value="cart">
                                    <input type="hidden" name="action" value="addToCart">
                                    <button value="'.$this->model->products['id'].'" type="submit" name="productId" class="btn btn-success btn-block buyButton"'. $this->model->optionToBuy .'>Buy</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    </div>';
    }

}
