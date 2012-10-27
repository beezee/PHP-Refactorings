<?php

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
 *For the sake of exercise, this follows as close to the version in
 *the Ruby edition as possible, however in reality it is just as
 *effective and likely more memory efficient
 *(http://www.phpclasses.org/blog/post/187-The-Secret-PHP-Optimization-of-version-54.html)
 *to simply handle the limitation logic in the __get method based on an
 *explicitly declared property which contains the available "dynamic methods"
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
echo $before->empty_name();
echo $before->empty_age();
echo $before->empty_phone_number();
//will continue to work for anything empty_*
//over time this can become difficult to manage if
// you ever explicitly define any empty_* methods

class PersonAfter
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

$after = new PersonAfter();
echo $after->empty_name();
echo $after->empty_age();
try{
    echo $after->empty_phone_number(); //throws exception
}catch(Exception $e){ echo $e->__toString(); }
