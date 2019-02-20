<?php
require_once 'products.php';
require_once 'incl/money_format.php';

function showDetailProduct($data)
{
    $product = array();
    if (!empty($data['product'])) {
        $product = $data['product'];
    }

    $optionToBuy = '';
    if (!$data['loggedIn']) {
        $optionToBuy = 'disabled ';
    }

echo '<div class="container">
        <div class="card shadow my-5">
            <div class="card-body p-5">

                <div class="row">
                    <div class="col-4">
                        <img src="./assets/images/' . $product['image_name'] . '.png" class="img-fluid" alt="Responsive image">
                    </div>
                    <div class="col-8">
                        <div class="card">
                            <div class="card-header">
                                <h2>'.$product['name'].' <span class="badge badge-secondary">Exclusive</span></h2>
                            </div>
                            <div class="card-body">
                                <h5 class="card-textl">'.$product['description'].'</h5>
                                <hr>
                                <h3>&#8364; '. money_format('%.2n', $product['price']) . '</h3>
                                <hr>';
                                if ($data['loggedIn']) {
                                    showReatingPanel();
                                }

                            echo'<form action="index.php" method="post">
                                    <input type="hidden" name="page" value="cart">
                                    <input type="hidden" name="order[action]" value="addToCart">
                                    <button value="'.$product['id'].'" type="submit" name="order[productId]" class="'. $optionToBuy .'btn btn-success buyButton">Buy</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    ';

    echo '          </div>
                </div>
            </div>
        </div>';
}
?>