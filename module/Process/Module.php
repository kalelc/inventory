<?php
namespace Process;

use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\ResultSet\ResultSet;
use Application\Db\TableGateway;
use Zend\EventManager\EventInterface;
use Process\Form\ReceiveInventoryForm;
use Process\Model\ReceiveInventory;
use Process\Model\ReceiveInventoryTable;
use Process\Form\OutputInventoryForm;
use Process\Model\OutputInventory;
use Process\Model\OutputInventoryTable;
use Process\Form\DetailsReceiveInventoryForm;
use Process\Model\DetailsReceiveInventory;
use Process\Model\DetailsReceiveInventoryTable;
use Process\Model\DetailsOutputInventory;
use Process\Model\DetailsOutputInventoryTable;
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

	public function init(ModuleManager $moduleManager)
	{
		$sharedEvents = $moduleManager->getEventManager()->getSharedManager();
		$sharedEvents->attach(__NAMESPACE__, 'dispatch', function ($e)
		{
			$controller = $e->getTarget();
			$controller->layout('layout/admin');

			$authenticationService = new AuthenticationService();
			if(!$authenticationService->hasIdentity()){
				$controller->plugin('redirect')->toRoute('security/login');
			}
			else {
				$resources = preg_replace('/(?<!^)([A-Z])/', '-\\1',explode("Controller",$controller->getClassName()));
				$resource = strtolower(str_replace("-","_", $resources[0]));

				$userObject = $authenticationService->getStorage()->read();
				$acl = unserialize($userObject->acl);

				if(!$acl->hasResource($resource)) {
					$controller->plugin('redirect')->toRoute('security/login');
				}
			}

		}, 100);
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
					$table = new ReceiveInventoryTable($tableGateway);
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
					$productTable = $sm->get('Admin\Model\ProductTable');

					$table = new DetailsReceiveInventoryTable($tableGateway);
					$table->setProductTable($productTable);
					return $table;
				},
				'DetailsReceiveInventoryTableGateway' => function ($sm)
				{
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new DetailsReceiveInventory());

					return new TableGateway('details_receive_inventory', $dbAdapter,null, $resultSetPrototype, null);
				},
				'Process\Form\OutputInventoryForm' =>  function($sm) {

					//client
					$client = $sm->get('config')['customers']['client'];
					$transporter = $sm->get('config')['customers']['transporter'];

					$customerTable = $sm->get("Admin/Model/CustomerTable");
					$clients = $customerTable->fetchAll($client);
					$clientsList = array();

					foreach($clients as $client){
						$clientsList[$client->getId()] = $client->getLastName()." ".$client->getLastName();
					}

					//seller
					$sellers = $customerTable->fetchAll($transporter);
					$selllerList = array();

					foreach($sellers as $selller){
						$selllerList[$selller->getId()] = $selller->getCompany() == false ? $selller->getCompany() : $selller->getFirstName()." ".$selller->getLastName();
					}

					//payment method
					$paymentMethodTable = $sm->get("Admin/Model/PaymentMethodTable");
					$paymentMethods= $paymentMethodTable->fetchAll();
					$paymentMethodList = array();

					foreach($paymentMethods as $paymentMethod){
						$paymentMethodList[$paymentMethod->getId()] = $paymentMethod->getName();
					}

					$form = new OutputInventoryForm($clientsList,$paymentMethodList,$selllerList);
					return $form;
				},
				'Process\Model\OutputInventoryTable' => function ($sm)
				{
					$tableGateway = $sm->get('OutputInventoryTableGateway');
					$table = new OutputInventoryTable($tableGateway);
					return $table;
				},
				'OutputInventoryTableGateway' => function ($sm)
				{
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new OutputInventory());

					return new TableGateway('output_inventory', $dbAdapter,null, $resultSetPrototype, null);
				},
				'Process\Model\DetailsOutputInventoryTable' => function ($sm)
				{
					$tableGateway = $sm->get('DetailsOutputInventoryTableGateway');
					$productTable = $sm->get('Admin\Model\ProductTable');

					$table = new DetailsOutputInventoryTable($tableGateway);
					$table->setProductTable($productTable);
					return $table;
				},
				'DetailsOutputInventoryTableGateway' => function ($sm)
				{
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new DetailsOutputInventory());

					return new TableGateway('details_output_inventory', $dbAdapter,null, $resultSetPrototype, null);
				},
			),
		);
	}
}