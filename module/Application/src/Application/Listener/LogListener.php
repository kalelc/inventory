<?php
namespace Application\Listener;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;

class LogListener implements ListenerAggregateInterface
{
    protected $listeners = array();
     
    public function attach(EventManagerInterface $e)
    {  
        $this->listeners[] = $e->attach('log.save', array($this, 'save'));
    }
     
    public function detach(EventManagerInterface $e) 
    {
        foreach ($this->listeners as $index => $listener) {
            if ($e->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }
     
    public function save(EventInterface $e) 
    { 
        error_log("LogListener save") ;
        echo 'save...'; 
    }
}