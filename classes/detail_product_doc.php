<?php
require_once "abstract_product_doc.php";
require_once "incl/money_format.php";

class DetailProductDoc extends productDoc
{
    public function __construct(PageModel $model)
    {
        // pass the data on to our parent class (basicDoc)
        parent::__construct($model);
    }

    public function mainContent()
    {
        //TODO: deze staat ook "hier"
        if ($this->model->allowedToBuy) {
            $optionToBuy = '';
        } else {
            $optionToBuy = 'disabled';
        }

        if (!isset($this->model->products)){
            echo 'no products found.';
            return;
        }

        echo '
                <div class="row">
                    <div class="col-4">
                        <img src="./assets/images/' . $this->model->products->image_name . '.png" class="img-fluid" alt="Responsive image">
                    </div>
                    <div class="col-8">
                        <div class="card">
                            <div class="card-header">
                                <h2>' . $this->model->products->name . ' <span class="badge badge-secondary">Exclusive</span></h2>
                            </div>
                            <div class="card-body">
                                <h5 class="card-textl">' . $this->model->products->description . '</h5>
                                <hr>
                                <h3>&euro; '. money_format('%!.2n',$this->model->products->price) . '</h3>
                                <hr>';
                                if($this->model->allowedToBuy) {
                                    $this->showRatingPanel();
                                }
                                $this->showBuyButton($this->model->products->id,$optionToBuy);
                          echo '</div>
                        </div>
                    </div>
                </div>';
    }

}
