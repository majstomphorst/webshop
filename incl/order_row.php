<?php 

class OrderRow {

    public $id = null;
    public $name = null;
    public $amount = null;
    public $unitPrice = null;
    public $linePrice = null; 

    public function __construct($product) {
        $this->id = $product->id;
        $this->name = $product->name;
        $this->unitPrice = $product->price;
    }

    public function convertToArrayForStorage() {
        $orderRow = array('product_id' => $this->id,
                          'amount' => $this->amount,
                          'unit_price' => $this->unitPrice);
        return $orderRow;
    }
}