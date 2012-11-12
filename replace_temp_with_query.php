<?php

require_once('assert.php');

/* Replace Temp with Query -
    this example as well as most use cases is paired with extract method
*/

class Purchase
{
    
    private $_item_price = 450;
    private $_quantity;
    
    public function __construct($quantity)
    {
        $this->_quantity = $quantity;
    }
    
    public function price()
    {
        $base_price = $this->_quantity * $this->_item_price;
        if ($base_price > 1000)
            $discount_factor = .95;
        else
            $discount_factor = .98;
        return $base_price * $discount_factor;
    }
}

class Purchase_Refactored
{
    
    private $_item_price = 450;
    private $_quantity;
    
    public function __construct($quantity)
    {
        $this->_quantity = $quantity;
    }
    
    public function base_price()
    {
        return $this->_quantity * $this->_item_price;
    }
    
    public function discount_factor()
    {
        return ($this->base_price() > 1000)
            ? .95 : .98;
    }
    
    public function price()
    {
        return $this->base_price() * $this->discount_factor();
    }  
}

$purchase = new Purchase(12);
$purchase_refactored = new Purchase_Refactored(12);

assert(5130 === intval($purchase->price()));
assert($purchase->price() === $purchase_refactored->price());

global $fail_count;
echo ($fail_count === 0) ?
    'All assertions passed' : "$fail_count assertions failed";