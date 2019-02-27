<?php
require_once 'classes/basic_doc.php';
require_once "incl/money_format.php";

class CartDoc extends BasicDoc
{
    public function __construct(PageModel $model)
    {
        // pass the data on to our parent class (basicDoc)
        parent::__construct($model);
    }

    public function mainContent()
    {
        echo '
            <table class="table">
                <thead class="thead-light">
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Total</th>
                </tr>
                </thead>
                <tbody>';

                
        if (count($this->model->cart) >= 0) { /* JH: Zier opmerking in product_model.php 107 wordt deze if vervanger door: foreach ($this->model->cartRows as $cartRow) { $this->showRow($cartRow) } */
            foreach ($this->model->cart as $cartRow) {
                    $this->showRow($cartRow);
                }
        }
        /* JH Extra: Toon ook het total van de Cart hier aan de gebruiker (opgeslagen in $model->total) */
        echo '</tbody>
            </table>
            <form action="index.php" method="post">
                <input type="hidden" name="page" value="cart">
                <input type="hidden" name="action" value="placeOrder">
                <button type="submit" name="productId" class="btn btn-success">Order!</button>
            </form>';
    }

    private function showRow($cartRow)
    {
        echo '
    <tr>
        <th>' . $cartRow['name'] . '</th>
        <th>&euro; ' . money_format('%.2n' /* JH: Volgens mij moet de format '%!.2n' zijn om het euroteken niet te tonen */, $cartRow['price']) . '</th>
        <th>
            <div class="row">
                <div class="col-sm">
                    <form class="form-inline" action="index.php" method="post">
                        <input type="hidden" name="page" value="cart">
                        <input type="hidden" name="action" value="removeFromCart">
                        <button value="' . $cartRow['id'] . '" type="submit" name="productId" class="btn btn-outline-danger btn-block">&#8678;</button>
                    </form>
                </div>
                <div class="col-sm col-5">
                <p class="text-center">' . $cartRow['amount'] . '</p>

                </div>
                <div class="col-sm">
                    <form class="form-inline" action="index.php" method="post">
                        <input type="hidden" name="page" value="cart">
                        <input type="hidden" name="action" value="addToCart">
                        <button value="' . $cartRow['id'] . '" type="submit" name="productId" class="btn btn-outline-success btn-block">&#8680;</button>
                    </form>
                </div>
            </div>
        </th>
        <th>&#8364; ' . money_format('%.2n' /* JH: Volgens mij moet de format '%!.2n' zijn om het euroteken niet te tonen */, $cartRow['total']) . '</th>
    </tr>
    ';

    }

}
