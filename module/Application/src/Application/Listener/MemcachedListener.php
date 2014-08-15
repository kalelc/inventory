<?php
namespace Application\Listener;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\Cache\Storage\Adapter\Memcached;

class MemcachedListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    protected $serviceManager;
    protected $memcached;

    public function __construct(Memcached $memcached)
    {
        $this->memcached = $memcached;
    }

    public function setServiceManager($serviceManager)
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }

    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('save', array($this,'save'), - 100);
    }

    public function save(EventInterface $e)
    {

        error_log("function save");
        /*
        $params = $e->getParams();
        $content = $params['__RESULT__'];
        unset($params['__RESULT__']);

        $modifiedOptions = false;

        if (isset($params['ttl']) && is_numeric($params['ttl']) && $params['ttl'] > 0)
        {
            try {
                $options = $this->cache->getOptions();
                $defaultTtl = $options->getTtl();
                $options->setTtl($params['ttl']);
                $this->cache->setOptions($options);
                unset($params['ttl']);
                $modifiedOptions = true;
            } catch (\Exception $e) {
                error_log("error setting new ttl: " . $e->getMessage());
            }
        }

        $id = md5(get_class($e->getTarget()) . '-' . json_encode($params));

        try {
            $supportedDatatypes = $this->cache->getCapabilities()->getSupportedDatatypes();

            if ($supportedDatatypes['object']) {
                $setIt = $this->cache->setItem($id, $content);
            } else {
                $setIt = $this->cache->setItem($id, serialize($content));
            }
        } catch (\Exception $e) {
            error_log("error saving to memcached");
            error_log($e->getMessage());
        }

        if ($modifiedOptions && isset($defaultTtl))
        {
            $options->setTtl($defaultTtl);
            $this->cache->setOptions($options);
        }
        */
    }
}