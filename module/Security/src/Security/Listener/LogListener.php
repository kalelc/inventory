<?php
namespace Security\Listener;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\Authentication\AuthenticationService;
use Security\Model\Log;
use Security\Model\LogTable;

class LogListener implements ListenerAggregateInterface
{
    protected $listeners = array();
    protected $serviceManager ;

    public function __construct($serviceManager) //public function __construct(LogTable $logTable) 
    {
        $this->serviceManager = $serviceManager;
        //$this->logTable = $logTable;
    }

     
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
        $log = new Log();
        $logTable = $this->serviceManager->get("Security\Model\LogTable");
        $params = $e->getParams();

        $authenticationService = new AuthenticationService();
        $user = $authenticationService->getIdentity()->id;

        if(isset($params['id']) && !empty($params['id'])){
            $log->setTableId($params['id']);
        }

        $log->setTable($params['table']);
        $log->setOperation($params['operation']);
        $log->setUser($user);

        $logTable->save($log);
    }
}