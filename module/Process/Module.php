<?php
namespace Process;

use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\EventManager\EventInterface;

//use Admin\Model\PaymentMethod;

use Zend\ModuleManager\ModuleManager;

use Application\ConfigAwareInterface;

use Zend\Authentication\AuthenticationService;

class Module
{
	public function getConfig()
	{
		return include __DIR__ . '/config/module.config.php';
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

	public function init(ModuleManager $moduleManager)
	{


	}

	public function getServiceConfig()
	{
		return array(
			'factories' => array(
				/*
				'Admin\Model\CustomerClassificationTable' =>  function($sm) {
					$tableGateway = $sm->get('CustomerClassificationTableGateway');
					$table = new CustomerClassificationTable($tableGateway);
					return $table;
				},
				'CustomerClassificationTableGateway' => function ($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new CustomerClassification());
					return new TableGateway('customers_classifications', $dbAdapter, null, $resultSetPrototype);
				},
				*/
				),
			);
	}
}