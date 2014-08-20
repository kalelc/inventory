<?php
namespace Process;

use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\EventManager\EventInterface;

use Process\Form\ReceiveInventoryForm;
use Process\Form\DetailsReceiveInventoryForm;

use Process\Model\ReceiveInventory;
use Process\Model\ReceiveInventoryTable;

use Process\Model\DetailsReceiveInventory;
use Process\Model\DetailsReceiveInventoryTable;

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

	public function onBootstrap(MvcEvent $e)
	{
		$em = $e->getApplication()->getEventManager();
		$sharedManager = $em->getSharedManager();
		$sm = $e->getApplication()->getServiceManager();

		$sharedManager->attach('Process\Controller\ReceiveInventoryController', 'dispatch', function ($e) use($sm, $em)
		{
			$controller = $e->getTarget();
			$cacheListener = $sm->get('Application\Listener\MemcachedListener');
			$controller->getEventManager()->attachAggregate($cacheListener);
		}, 2);


	}

	public function getServiceConfig()
	{
		return array(
			'factories' => array(
				'Process\Form\ReceiveInventoryForm' =>  function($sm) {


					$client = $sm->get('config')['customers']['client'];
					$transporter = $sm->get('config')['customers']['transporter'];

					$customerTable = $sm->get("Admin/Model/CustomerTable");
					$customers = $customerTable->fetchAll($client);
					$customersList = array();

					foreach($customers as $customer){
						$customersList[$customer->getId()] = $customer->getLastName()." ".$customer->getLastName();
					}

					$paymentMethodTable = $sm->get("Admin/Model/PaymentMethodTable");
					$paymentMethods= $paymentMethodTable->fetchAll();
					$paymentMethodList = array();

					foreach($paymentMethods as $paymentMethod){
						$paymentMethodList[$paymentMethod->getId()] = $paymentMethod->getName();
					}

					$shipments = $customerTable->fetchAll($transporter);
					$shipmentList = array();

					foreach($shipments as $shipment){
						$shipmentList[$shipment->getId()] = $shipment->getCompany();

						$shipmentList[$shipment->getId()] = $shipment->getCompany() == false ? $shipment->getCompany() : $shipment->getFirstName()." ".$shipment->getLastName();
					}

					$form = new ReceiveInventoryForm($customersList,$paymentMethodList,$shipmentList);
					return $form;
				},
				'Process\Form\DetailsReceiveInventoryForm' => function($sm) {
					$productTable = $sm->get("Admin/Model/ProductTable");

					$productList = $productTable->getName();
					$detailsReceiveInventoryForm = new DetailsReceiveInventoryForm($productList);
					return $detailsReceiveInventoryForm;

				},
				'Process\Model\ReceiveInventoryTable' => function ($sm)
				{
					$tableGateway = $sm->get('ReceiveInventoryTableGateway');
					$cache = $sm->get('Cache\Adapter\Memcached');
					$eventManager = $sm->get("Zend\EventManager\EventManager");

					$table = new ReceiveInventoryTable($tableGateway);
					$table->setCache($cache);
					$table->setEventManager($eventManager);
					return $table;
				},
				'ReceiveInventoryTableGateway' => function ($sm)
				{
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new ReceiveInventory());

					return new TableGateway('receive_inventory', $dbAdapter,null, $resultSetPrototype, null);
				},
				'Process\Model\DetailsReceiveInventoryTable' => function ($sm)
				{
					$tableGateway = $sm->get('DetailsReceiveInventoryTableGateway');
					$cache = $sm->get('Cache\Adapter\Memcached');
					$productTable = $sm->get('Admin\Model\ProductTable');
					$eventManager = $sm->get("Zend\EventManager\EventManager");

					$table = new DetailsReceiveInventoryTable($tableGateway);
					$table->setProductTable($productTable);
					$table->setCache($cache);
					$table->setEventManager($eventManager);
					return $table;
				},
				'DetailsReceiveInventoryTableGateway' => function ($sm)
				{
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new DetailsReceiveInventory());

					return new TableGateway('details_receive_inventory', $dbAdapter,null, $resultSetPrototype, null);
				},
				),
);
}
}