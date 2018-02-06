<?php
/**
 * @file Luminance/Dependency/Container.php
 * @namespace Luminance\Dependency
 * @version 1.0.0
 * @copyright 2017-2018
 * @license MIT
 */

namespace Luminance\Dependency;

use Luminance\Dependency\Exceptions\InvalidContainerProperty;
use Luminance\Dependency\Exceptions\ProtectedProperty;
use Luminance\Dependency\Exceptions\UnacceptableOverride;

class Container
{
    private $dependencies   = [];
    private $constructed    = [];

    public function __construct()
    {
    }

    public function addDependency(string $dependency_name) {
        $this->dependencies[] = $dependency_name;
    }

    public function __constructCaller(string $caller)
    {
        $this->constructed[] = $caller;
        $this->$caller = new $caller;
        return $this->$caller;
    }

    /**
     * Magic getter, will construct strictly as needed
     *
     * @param string $action
     * @return mixed
     *
     * @throws InvalidContainerProperty
     */
    public function __get(string $action)
    {
        if(isset($this->$action))
        {
            return $this->$action;
        }
        else if(in_array($action, $this->dependencies))
        {
            return $this->__constructCaller($action);
        }
        else
        {
            throw new InvalidContainerProperty('Invalid property ' . $action . ' on container.', 5);
        }
    }

    /**
     * Magic setter, will update self and constructed,
     * and provide sync between dependencies
     *
     * @param string $action
     * @param $value
     *
     * @throws UnacceptableOverride|ProtectedProperty
     */
    public function __set(string $action, $value)
    {
        if(isset($this->$action))
        {
            $this->$action = $value;
            foreach($this->constructed as $item)
            {
                $act_inj = "di_".$action;
                $this->$item->$act_inj = $value;
            }
        }
        else if(in_array($action, $this->constructed))
        {
            throw new UnacceptableOverride('Cannot override a constructed dependency without using an event-driven method', 5);
        }
        else if(in_array($action, $this->dependencies))
        {
            throw new ProtectedProperty('Cannot override a dependency as property', 5);
        }
        else {
            $self = self::class;
            trigger_error(
                'Failed to set ' . $action      .
                ' in class ' . $self . '. This is likely' .
                ' due to it not being a valid property,'  .
                ' please ensure it exists already OR is'  .
                ' is available as a property in a child'  .
                ' method',
                E_USER_NOTICE
            );
        }
    }

    public function __destruct()
    {
        foreach($this->constructed as $constructed_item)
        {
            if(method_exists($this->$constructed_item, "di_complete"))
            {
                $this->$constructed_item->di_complete(); // manual hook before deconstruct
            }
            unset($this->$constructed_item); // force the __destruct on the class
        }
    }
}
