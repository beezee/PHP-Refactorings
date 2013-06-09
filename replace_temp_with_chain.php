<?php

require_once('assert.php');

/* Replace Temp with Chain */

class Select
{
    private $_options = array();
    
    public function add_option($option)
    {
        $this->_options[] = $option;
    }
    
    public function options()
    {
        return $this->_options;
    }
}

class Select_Chainable
{
    private $_options = array();
    
    public static function with_option($option)
    {
        $r = new self();
        $r->and_option($option);
        return $r;
    }
    
    public function and_option($option)
    {
        $this->_options[] = $option;
        return $this;
    }
    
    public function options()
    {
        return $this->_options;
    }
}

$select = new Select();
$select->add_option(1999);
$select->add_option(2000);
$select->add_option(2001);
$select->add_option(2002);

$select_chained = Select_Chainable::with_option(1999)->and_option(2000)->and_option(2001)->and_option(2002);
assert($select->options() === $select_chained->options());
