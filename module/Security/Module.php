<?php
namespace Security;

use Zend\Mvc\ModuleRouteListener;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Mvc\MvcEvent;
use Security\Adapter\AuthSessionAdapter;
use Security\Model\RolTable;
use Security\Model\Rol;
use Security\Model\UserTable;
use Security\Model\User;
use Security\Form\UserForm;
use Application\Listener\LogListener;
use Zend\ModuleManager\ModuleManager;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        /*
        $eventManager = $e->getApplication()->getEventManager();
        $sharedManager = $eventManager->getSharedManager();
        $serviceManager = $e->getApplication()->getServiceManager();
        
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        $sharedManager->attach('*', 'dispatch', function ($e) use($serviceManager, $eventManager)
        {
            $controller = $e->getTarget();
            $userAuditListener = $serviceManager->get('Application\Listener\LogListener');
            $controller->getEventManager()->attachAggregate($userAuditListener);
        }, 2);*/
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
                'Application\Listener\LogListener' => function ($sm)
                {
                    $logListener = new LogListener();
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