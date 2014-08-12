<?php
namespace Process\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Process\Model\ReceiveInventory;
use Process\Model\ReceiveInventoryTable;
use Zend\File\Transfer\Adapter\Http  as HttpTransfer;
use Zend\Validator\File\Size;
use Zend\Validator\File\Extension;
use Process\Form\ReceiveInventoryForm;
use Process\Traits\ModuleTablesTrait as ProcessTablesTrait;
use Admin\Traits\ModuleTablesTrait as AdminTablesTrait;
use Application\ConfigAwareInterface;
use Zend\Session\Container;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Iterator as PaginatorIterator;

class ReceiveInventoryController extends AbstractActionController
implements ConfigAwareInterface
{
	use ProcessTablesTrait, AdminTablesTrait;
	private $config;

	public function indexAction()
	{
		dumpx($this->getProductTable()->getAll());
		dump(getenv('APPLICATION_ENV'));
		exit();
	}

	public function addAction()
	{
		$viewModel = new ViewModel();

		$form = $this->getServiceLocator()->get("Process\Form\ReceiveInventoryForm");
		$viewModel->setVariable('form', $form);

		$request = $this->getRequest();

		if ($request->isPost()) {

			$receiveInventory = new ReceiveInventory();
			$form->setInputFilter($receiveInventory->getInputFilter());

			$data = $request->getPost()->toArray();

			$form->setData($data);

			if ($form->isValid()) {

				$fileService = $this->getServiceLocator()->get('Admin\Service\FileService');

				$fileService->setDestination($this->config['component']['receive_inventory']['file_path']);
				$fileService->setSize($this->config['file_characteristics']['file']['size']);
				$fileService->setExtension($this->config['file_characteristics']['file']['extension']);

				$invoiceFile = $fileService->copy($this->params()->fromFiles('invoice_file'));

				$data['invoice_file'] = $invoiceFile ? $invoiceFile : "" ;

				$receiveInventory->exchangeArray($data);

				$receiveInventoryId = $this->getReceiveInventoryTable()->save($receiveInventory);

				$container = new Container('receive_inventory');
                $container->id = $receiveInventoryId;
                $container->registerDate = $receiveInventory->getRegisterDate();
				$container->customer = $receiveInventory->getCustomer();
				$container->paymentMethod = $receiveInventory->getPaymentMethod();
				$container->shipment = $receiveInventory->getShipment();
				$container->guide = $receiveInventory->getGuideNumber();
				$container->invoice = $receiveInventory->getInvoice();
                
				return $this->redirect()->toRoute('process/receive_inventory/add/details');
			}
		}
		$viewModel->setVariable('config', $this->config);

		return $viewModel;
	}

	public function addDetailsAction() 
	{

		/*test container*/
		$container = new Container('receive_inventory');

		$receiveInventoryForm = $this->getServiceLocator()->get("Process\Form\ReceiveInventoryForm");
		
		/*
		$receiveInventoryForm->get('register_date')->setValue($container->registerDate);
		$receiveInventoryForm->get('customer')->setValue($container->customer);
		$receiveInventoryForm->get('payment_method')->setValue($container->paymentMethod);
		$receiveInventoryForm->get('shipment')->setValue($container->shipment);
		$receiveInventoryForm->get('guide_number')->setValue($container->guide);
		$receiveInventoryForm->get('invoice')->setValue($container->invoice);

		$receiveInventoryForm->get('customer')->setAttributes(array('disabled' => 'disabled'));
		$receiveInventoryForm->get('payment_method')->setAttributes(array('disabled' => 'disabled'));
		$receiveInventoryForm->get('shipment')->setAttributes(array('disabled' => 'disabled'));
		$receiveInventoryForm->get('guide_number')->setAttributes(array('disabled' => 'disabled'));
		$receiveInventoryForm->get('invoice')->setAttributes(array('disabled' => 'disabled'));

		$viewModelReceiveInventory = new ViewModel();
		$viewModelReceiveInventory->setTemplate("process/receive-inventory/add");
		$viewModelReceiveInventory->setVariable('form', $receiveInventoryForm);

		return $viewModelReceiveInventory;*/

		/******/
		$form = $this->getServiceLocator()->get('Process\Form\DetailsReceiveInventoryForm');
		

		$viewModel = new ViewModel();
		$viewModel->setVariable('form',$form);
		$viewModel->setVariable('config', $this->config);

		$viewModel->setTemplate("process/receive-inventory/details");

		return $viewModel;

	}


	public function setConfig($config)
	{
		$this->config = $config;
	}
}