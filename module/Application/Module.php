<?php
namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Cache\Storage\Adapter\MemcachedOptions;
use Zend\Cache\Storage\Adapter\Memcached;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
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
                'Cache\Adapter\MemcachedOptions' => function ($sm)
                {
                    $config = $sm->get('config');

                    return new MemcachedOptions($config['memcached']);
                },
                'Cache\Adapter\Memcached' => function ($sm)
                {
                    return new Memcached($sm->get('Cache\Adapter\MemcachedOptions'));
                },
                ),
            );
    }
}
