<?php
namespace Settings;

use Zend\Mvc\ModuleRouteListener;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Mvc\MvcEvent;
use Settings\Adapter\AuthSessionAdapter;
use Settings\Model\UserShortCutTable;
use Settings\Model\UserShortCut;
use Zend\ModuleManager\ModuleManager;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }


    public function init(ModuleManager $moduleManager)
    {
        $sharedEvents = $moduleManager->getEventManager()->getSharedManager();

        $sharedEvents->attach(__NAMESPACE__, 'dispatch', function ($e)
        {
            $controller = $e->getTarget();
            $controller->layout('layout/layout');
        }, 100);
    }
    

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Settings\Adapter\AuthSessionAdapter' => function($sm)
                {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $authSessionAdapter = new AuthSessionAdapter($dbAdapter);

                    return $authSessionAdapter;
                },
                'Settings\Model\UserShortCutTable' => function ($sm)
                {
                    $tableGateway = $sm->get('UserShortCutTableGateway');
                    $table = new UserShortCutTable($tableGateway);
                    return $table;
                },
                'UserShortCutTableGateway' => function ($sm)
                {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new UserShortCut());
                    return new TableGateway('user_shortcuts', $dbAdapter, null, $resultSetPrototype, null);
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