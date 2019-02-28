<?php
include_once 'incl/session_manager.php';

function handleAjaxActions()
{
    $order = getPostVar('action');

    switch ($order) {
        case 'updateRating':
            $productId = getPostVar('productId');
            $rating = getPostVar('rating');
            $userId = getLoggedInUserId();
            updateOrStoreRating($productId,$rating,$userId);
            
            break;
        case 'getRatingInfo':
            $productIds = getPostVar('productIds');
            $userId = getLoggedInUserId();

            $userRatings = getUserRating($productIds,$userId);
            $avgRatings = getAvgProductRating($productIds);

            // create the correct data structure
            foreach ($avgRatings as $index => $productInfo) {
                $key = $productInfo['product_id'];
                if (array_key_exists($key,$userRatings)) {
                    $avgRatings[$index]['userRating'] = $userRatings[$key];
                }
            };

            echo json_encode($avgRatings);

            break;
        default:
            # code...
            break;
    }
}


?>