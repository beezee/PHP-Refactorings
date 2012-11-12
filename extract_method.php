<?php

require_once('assert.php');

/* Extract Method */

class Invoice
{
    private $_name;
    
    public function __construct($name)
    {
        $this->_name = $name;
    }
    
    public function print_banner()
    {
        return 'PHP Refactorings Invoice';
    }
    
    public function print_owing($amount)
    {
        $r = '';
        $r .= $this->print_banner();
        $r .= 'name: '.$this->_name;
        $r .= 'amount: '.$amount;
        return $r;
    }
}

class Invoice_Refactored
{
    private $_name;
    
    public function __construct($name)
    {
        $this->_name = $name;
    }
    
    public function print_banner()
    {
        return 'PHP Refactorings Invoice';
    }
    
    public function print_details($amount)
    {
        $r = '';
        $r .= 'name: '.$this->_name;
        $r .= 'amount: '.$amount;
        return $r;
    }
    
    public function print_owing($amount)
    {
        $r = '';
        $r .= $this->print_banner();
        $r .= $this->print_details($amount);
        return $r;
    }
}

$invoice = new Invoice('brian');
$invoice_refactored = new Invoice_Refactored('brian');
assert($invoice->print_owing(20) === $invoice_refactored->print_owing(20));

global $fail_count;
echo ($fail_count === 0) ?
    'All assertions passed' : "$fail_count assertions failed";