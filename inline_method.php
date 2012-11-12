<?php

require_once('assert.php');

/* Inline Method */

class DeliveryProvider
{
    
    private $_number_of_late_deliveries;
    
    public function __construct($late_deliveries)
    {
        $this->_number_of_late_deliveries = $late_deliveries;
    }
    
    public function get_rating()
    {
        return $this->more_than_five_late_deliveries()
            ? 2 : 1;
    }
    
    public function more_than_five_late_deliveries()
    {
        return $this->_number_of_late_deliveries > 5;
    }
}

class DeliveryProvider_Refactored
{
    private $_number_of_late_deliveries;
    
    public function __construct($late_deliveries)
    {
        $this->_number_of_late_deliveries = $late_deliveries;
    }
    
    public function get_rating()
    {
        return $this->_number_of_late_deliveries > 5
            ? 2 : 1;
    }
    
}

$rated_1 = new DeliveryProvider(3);
$rated_1_refactored = new DeliveryProvider_Refactored(2);
assert($rated_1->get_rating() === $rated_1_refactored->get_rating());
assert($rated_1->get_rating() === 1);

$rated_2 = new DeliveryProvider(7);
$rated_2_refactored = new DeliveryProvider_Refactored(9);
assert($rated_2->get_rating() === $rated_2_refactored->get_rating());
assert($rated_2->get_rating() === 2);

global $fail_count;
echo ($fail_count === 0) ?
    'All assertions passed' : "$fail_count assertions failed";