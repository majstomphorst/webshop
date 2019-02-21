<?php
require_once 'incl/money_format.php';


function showProducts($data)
{
    $products = array();
    if (isset($data['products'])){
        $products = $data['products'];
    } else {
        echo 'no products found.';
        return;
    }

    $optionToBuy = '';
    if (!$data['loggedIn']) {
        $optionToBuy = 'disabled';
    }
    
    echo '<div class="container">     
                <div class="card-deck">';
                    foreach ($products as $product ) {
                        showProductCard($product,$optionToBuy);
                    }
    echo        '</div>
            </div>';

}
function showProductCard($product, $optionToBuy)
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
                    <button value="'.$product['id'].'" type="submit" name="order[productId]" class="btn btn-success btn-block buyButton"'. $optionToBuy .'>Buy</button>
                </form>';

                // check if a user in loggein 
                if(!$optionToBuy) {
                    showReatingPanel();
                }
                
            echo'  <br>
                <a href="?page=detailProduct&id='. $product['id'] .'" ><button type="button" class="btn btn-info btn-sm btn-block">More information</button></a>
            </div>
        </div>
    </div>
    ';
}


function showReatingPanel()
{
    echo"
<!-- Rating Stars Box -->
<h5>AVG:<span class='avgRating'></span></h5>
<h5>Your rating:<span class='yourRating'></span></h5>
<div class='rating-stars text-center'>
    <ul id='stars'>
        <li class='star' title='Poor' data-value='1'>
            <i class='fa fa-star fa-fw'></i>
        </li>
        <li class='star' title='Fair' data-value='2'>
            <i class='fa fa-star fa-fw'></i>
        </li>
        <li class='star' title='Good' data-value='3'>
            <i class='fa fa-star fa-fw'></i>
        </li>
        <li class='star' title='Excellent' data-value='4'>
            <i class='fa fa-star fa-fw'></i>
        </li>
        <li class='star' title='WOW!!!' data-value='5'>
            <i class='fa fa-star fa-fw'></i>
        </li>
    </ul>
</div>
";
}
?>