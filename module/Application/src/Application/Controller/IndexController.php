<?php
namespace Application\Controller;
 
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\ConfigAwareInterface;
 
class IndexController extends AbstractActionController
    implements ConfigAwareInterface
{
    protected $config;
 
    public function setConfig($config)
    {
        $this->config = $config;
    }
}
?>