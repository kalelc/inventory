<?php
namespace Security;

use Zend\Mvc\ModuleRouteListener;
use Zend\Db\ResultSet\ResultSet;
use Application\Db\TableGateway;
use Zend\Mvc\MvcEvent;
use Security\Adapter\AuthSessionAdapter;
use Security\Model\RolTable;
use Security\Model\Rol;
use Security\Model\LogTable;
use Security\Model\Log;
use Security\Model\UserTable;
use Security\Model\User;
use Security\Form\UserForm;
use Security\Listener\LogListener;
use Zend\ModuleManager\ModuleManager;
use Zend\Authentication\AuthenticationService;

class Module
{

    public function init(ModuleManager $moduleManager)
    {
        $sharedEvents = $moduleManager->getEventManager()->getSharedManager();
        $sharedEvents->attach(__NAMESPACE__, 'dispatch', function ($e)
        {
            $controller = $e->getTarget();
            $controller->layout('layout/admin');
        }, 100);
    }

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $sharedManager = $eventManager->getSharedManager();
        $serviceManager = $e->getApplication()->getServiceManager();
        
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        $sharedManager->attach('*', 'dispatch', function ($e) use($serviceManager, $eventManager)
        {
            $controller = $e->getTarget();
            $logListener = $serviceManager->get('Security\Listener\LogListener');
            $controller->getEventManager()->attachAggregate($logListener);
        }, 2);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Security\Adapter\AuthSessionAdapter' => function($sm)
                {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $authSessionAdapter = new AuthSessionAdapter($dbAdapter);

                    return $authSessionAdapter;
                },
                'Security\Model\UserTable' => function ($sm)
                {
                    $tableGateway = $sm->get('UserTableGateway');
                    $table = new UserTable($tableGateway);
                    return $table;
                },
                'Security\Form\UserForm' => function($sm)
                {
                    $roles = $sm->get("Security\Model\RolTable")->fetchAll();
                    $rolesList = array();

                    foreach($roles as $rol) {
                        $rolesList[$rol->getId()] = $rol->getName();
                    }

                    $form = new UserForm($rolesList);
                    return $form;
                },
                'UserTableGateway' => function ($sm)
                {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new User());
                    return new TableGateway('users', $dbAdapter, null, $resultSetPrototype, null);
                },
                'Security\Model\RolTable' => function ($sm)
                {
                    $tableGateway = $sm->get('RolTableGateway');
                    $table = new RolTable($tableGateway);
                    return $table;
                },
                'RolTableGateway' => function ($sm)
                {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Rol());
                    return new TableGateway('roles', $dbAdapter, null, $resultSetPrototype, null);
                },
                'Security\Model\LogTable' => function ($sm)
                {
                    $tableGateway = $sm->get('LogTableGateway');
                    $table = new LogTable($tableGateway);
                    return $table;
                },
                'LogTableGateway' => function ($sm)
                {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Log());
                    return new TableGateway('logs', $dbAdapter, null, $resultSetPrototype, null);
                },
                'Security\Listener\LogListener' => function ($sm)
                {
                    //dumpx($sm->get("Security\Model\UserTable"));
                    $logListener = new LogListener($sm);
                    return $logListener;
                },
                ),
);
}

public function getAutoloaderConfig()
{
    return array(
        'Zend\Loader\StandardAutoloader' => array(
            'namespaces' => array(
                __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
}
}