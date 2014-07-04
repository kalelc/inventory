<?php
namespace Process;

use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\EventManager\EventInterface;

use Process\Form\ReceiveInventoryForm;

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
				'Admin\Form\ReceiveInventoryForm' =>  function($sm) {
					$customerTable = $sm->get("Admin/Model/CustomerTable");
					$customers = $customerTable->fetchAll();
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

					$customerTable = $sm->get("Admin/Model/CustomerTable");
					$shipments= $customerTable->fetchAll();
					$shipmentList = array();

					foreach($shipments as $shipment){
						$shipmentList[$shipment->getId()] = !empty($shipment->getCompany()) ? $shipment->getCompany() : $shipment->getLastName()." ".$shipment->getLastName();
					}

					$form = new ReceiveInventoryForm($customersList,$paymentMethodList,$shipmentList);
					return $form;
				},
			),
		);
	}
}