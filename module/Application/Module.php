<?php
namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Cache\Storage\Adapter\MemcachedOptions;
use Zend\Cache\Storage\Adapter\Memcached;
use Application\Listener\MemcachedListener;

use Application\Model\EventFeatureCacheAwareInterface;
use Zend\Db\TableGateway\Feature\EventFeature;
use Zend\Db\TableGateway\Feature\GlobalAdapterFeature;
use Zend\EventManager\EventManager;
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
            $controller->layout('layout/layout');
        }, 100);

        $sharedEvents->attach(array("Admin","Process") , 'dispatch', function ($e)
        {
            $controller = $e->getTarget();
            $authenticationService = new AuthenticationService();
            if($authenticationService->hasIdentity()){
                $authenticationService->getStorage()->read();
                $identity = $authenticationService->getIdentity();
                $controller->layout()->setVariable("identity",$identity);
            }
            else {
                $controller->plugin('redirect')->toRoute('security/login');
            }
        }, 100);

    }

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $serviceManager = $e->getApplication()->getServiceManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getControllerConfig()
    {
        return array(
            'initializers' => array(
                function ($instance, $sm) {
                    if ($instance instanceof ConfigAwareInterface) {
                        $locator = $sm->getServiceLocator();
                        $config  = $locator->get('Config');
                        $instance->setConfig($config);
                    }
                }
            )
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    public function getViewHelperConfig()
    {
        return array(
            'invokables' => array(
                'formElementErrors' => 'Application\Form\View\Helper\FormElementErrors'
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

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Cache\Adapter\MemcachedOptions' => function ($sm) {
                    $config = $sm->get('config');
                    return new MemcachedOptions($config['memcached']);
                },
                'Cache\Adapter\Memcached' => function ($sm) {
                    return new Memcached($sm->get('Cache\Adapter\MemcachedOptions'));
                },
                "Application\Listener\MemcachedListener" => function($sm) {
                    $memcached = $sm->get('Cache\Adapter\Memcached');
                    $cacheListener = new MemcachedListener($memcached);
                    return $cacheListener;

                },
                'Zend\EventManager\EventManager' => function($sm) {
                    $eventManager = new EventManager();
                    return $eventManager;
                }
            ),
        );
    }
}
