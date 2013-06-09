<?php

require_once('assert.php');

/* Replace Method with Method Object
 * This is not a realistic use of the refactoring, more appropriate for cleaning up
 * long methods with many temp variables. Simply for illustration purposes
*/

class User
{
    
    public $friends;
    public $first_name;
    public $last_name;
    public $full_name;
    public $confirmed;
    public $key;
    public $confirm_link;
    
    public function initialize($first_name, $last_name, $key)
    {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->full_name = $this->first_name.' '.$this->last_name;
        $this->friends = array('Tom Anderson');
        $this->confirmed = 'no';
        $this->key = $key;
        $this->confirm_link = 'http://example.com/confirm/'.md5($this->full_name.$this->key);
    }
}

class User_Refactored
{
    public $friends;
    public $first_name;
    public $last_name;
    public $full_name;
    public $confirmed;
    public $confirm_link;
    
    public function initialize($first_name, $last_name, $key)
    {
        $initializer = new User_Initializer();
        $initializer->run($this, $first_name, $last_name, $key);
    }
}

class User_Initializer
{
    public $user;
    public $first_name;
    public $last_name;
    public $key;
    
    public function set_friends($friends)
    {
        foreach($friends as $friend)
            $this->user->friends[] = $friend;
    }
    
    public function set_names()
    {
        $this->user->first_name = $this->first_name;
        $this->user->last_name = $this->last_name;
        $this->user->full_name = $this->first_name.' '.$this->last_name;
    }
    
    public function set_key()
    {
        $this->user->key = $this->key;
    }
    
    public function set_confirmed($confirmed)
    {
        $this->user->confirmed = $confirmed;
    }
    
    public function set_confirm_link()
    {
        $this->user->confirm_link
            = 'http://example.com/confirm/'.md5($this->user->full_name.$this->key);
    }
    
    public function run($user, $first_name, $last_name, $key)
    {
        $this->user = $user;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->key = $key;
        $this->set_names();
        $this->set_key();
        $this->set_friends(array('Tom Anderson'));
        $this->set_confirmed('no');
        $this->set_confirm_link();
    }
}

$user = new User();
$user->initialize('Brian', 'Zeligson', 'whyismarkwahlbergsoconfusedandangry');
$user2 = new User_Refactored();
$user2->initialize('Brian', 'Zeligson', 'whyismarkwahlbergsoconfusedandangry');

assert($user2->full_name === 'Brian Zeligson');
assert($user->full_name === $user2->full_name);
assert($user2->friends === array('Tom Anderson'));
assert($user->friends === $user2->friends);
assert($user2->confirm_link === 'http://example.com/confirm/'.md5('Brian Zeligson'
                                                        .'whyismarkwahlbergsoconfusedandangry'));
assert($user->confirm_link === $user2->confirm_link);

global $fail_count;
echo ($fail_count === 0) ?
    'All assertions passed' : "$fail_count assertions failed";