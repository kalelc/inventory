<?php
namespace Settings;

use Zend\Mvc\ModuleRouteListener;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Mvc\MvcEvent;
use Settings\Adapter\AuthSessionAdapter;
use Settings\Model\UserShortCutTable;
use Settings\Model\UserShortCut;
use Settings\Form\UserShortCutForm;
use Settings\Model\Module as SettingsModule;
use Settings\Model\ModuleTable as ModuleTable;
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
                'Settings\Model\ModuleTable' => function ($sm)
                {
                    $tableGateway = $sm->get('ModuleTableGateway');
                    $table = new ModuleTable($tableGateway);
                    return $table;
                },
                'ModuleTableGateway' => function ($sm)
                {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new SettingsModule());
                    return new TableGateway('modules', $dbAdapter, null, $resultSetPrototype, null);
                },
                'Settings\Form\UserShortCutForm' => function($sm)
                {
                    $modules = $sm->get("Settings\Model\ModuleTable")->fetchAll();
                    $listModules = array();

                    foreach($modules as $module) {
                            $listModules[$module->getId()] = $module->getName();
                    }

                    $users = $sm->get("Security\Model\UserTable")->fetchAll();
                    $listUsers = array();

                    dumpx($users,"users");

                    foreach($users as $user) {
                            $listUsers[$user->getId()] = $user->getFirstName()." ".$user->getLastName();
                    }
                    $userShortCutForm = new UserShortCutForm($listModules,$listUsers);
                    return $userShortCutForm;
                }
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