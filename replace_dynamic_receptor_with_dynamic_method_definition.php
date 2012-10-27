<?php

require_once('assert.php');

/*Replace Dynamic Receptor with Dynamic Method Definition
 *
 *This still uses a dynamic receptor because of PHP's current
 *inability to add true class methods on the fly,
 *however by limiting what the dynamic receptor will handle,
 *we effectively remove the smell this particular refactoring addresses,
 *which is a difficulty maintaining which method calls are handled
 *by the dynamic receptor vs which are handled by explicitly defined
 *class methods
 *
 *For the sake of exercise, PersonAfterTranslated follows as close to the version in
 *the Ruby edition as possible, however in reality it is just as
 *effective and likely more memory efficient
 *(http://www.phpclasses.org/blog/post/187-The-Secret-PHP-Optimization-of-version-54.html)
 *to simply handle the limitation logic in the __get method based on an
 *explicitly declared property which contains the available "dynamic methods"
 *as outlined in the PersonAfterAdapted class
 *
 */

class PersonBefore
{
    public function is_empty($property)
    {
        return empty($this->$property);
    }
    
    public function __call($method, $args)
    {
        return $this->is_empty(preg_replace('/^empty\_/', '', $method));
    }
}

$before = new PersonBefore();
assert($before->empty_name());
assert($before->empty_age());
assert($before->empty_phone_number());

//will continue to work for anything empty_*
//over time this can become difficult to manage if
// you ever explicitly define any empty_* methods

class PersonAfterTranslated
{
    private $additional_functions = array();
    
    private function properties_with_empty_predicate(array $properties)
    {
        $self = $this;
        foreach($properties as $property)
        {
            $method = 'empty_'.$property;
            $this->$method = function() use ($self, $property) {
                return empty($self->$property);
            };
        }
    }
    
    public function __construct()
    {
        $this->properties_with_empty_predicate(array('name', 'age'));
    }
    
    public function __call($method, $args)
    {
        if ($this->{$method} instanceof Closure)
                return call_user_func_array($this->{$method},$args);
        throw new Exception('Call to undefined method '.__CLASS__.'::'.$method);
    }
}

$after = new PersonAfterTranslated();
assert($after->empty_name());
assert($after->empty_age());
try{
    $after->empty_phone_number();
    assert(false, 'empty_phone_number should not be available on PersonAfterTranslated class');
}catch(Exception $e){ }

class PersonAfterAdapted
{
    private $properties_with_empty_predicate = array('name', 'age');
    
    private function is_empty($property)
    {
        return (!property_exists($this, $property) or empty($this->$property));
    }
    
    public function __call($method, $args)
    {
        if (in_array($property = preg_replace('/^empty_/', '', $method), $this->properties_with_empty_predicate))
                return $this->is_empty($property);
        throw new Exception('Call to undefined method '.__CLASS__.'::'.$method);
    }
}

$after = new PersonAfterAdapted();
assert($after->empty_name());
assert($after->empty_age());
try{
    $after->empty_phone_number();
    assert(false);
}catch(Exception $e){ }

global $fail_count;
echo ($fail_count === 0) ?
    'All assertions passed' : "$fail_count assertions failed";


