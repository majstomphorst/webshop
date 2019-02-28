<?php 

class RatingInfo {

    public $product_id;
    public $avgRating;
    public $userRating;

    public function __construct($avgRating) {
        $this->product_id = $avgRating->product_id;
        $this->avgRating = $avgRating->avgRating;
    }
}