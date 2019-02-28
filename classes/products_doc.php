<?php
require_once "abstract_product_doc.php";
require_once "incl/money_format.php";

class ProductsDoc extends ProductDoc
{
    public function __construct(PageModel $model)
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

        $this->startContainer();

        foreach ($this->model->products as $product) {
            $this->showProductCard($product);
        }

        $this->endContainer();

    }

    private function showProductCard($product)
    {
        //TODO: deze staat ook "hier"
        if ($this->model->allowedToBuy) {
            $optionToBuy = '';
        } else {
            $optionToBuy = 'disabled';
        }

        setlocale(LC_MONETARY, 'nl_NL');
        
        echo '
        <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3 col-offset-0">
            <div class="card border-dark text-center">
                <img src="./assets/images/' . $product->image_name . '.png" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">' . $product->name . '</h5>
                    <p class="card-text">' . $product->description . '</p>
                    <div class="card-footer bg-transparent border-success">'. money_format('%!.2n', $product->price) .'</div>';
                    
                    $this->showBuyButton($product->id,$optionToBuy);
                    
                    $this->showRatingPanel();
                echo'  <br>
                    <a href="?page=detailProduct&id='. $product->id .'" ><button type="button" class="btn btn-info btn-sm btn-block">More information</button></a>
                </div>
            </div>
        </div>
        ';
    }
}