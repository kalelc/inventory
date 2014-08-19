<?php
namespace Process\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Process\Model\ReceiveInventory;
use Process\Model\ReceiveInventoryTable;
use Process\Model\DetailsReceiveInventory;
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
		$this->getReceiveInventoryTable()->get(10);
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

				return $this->redirect()->toRoute('process/receive_inventory/add/details');
			}
		}
		$viewModel->setVariable('config', $this->config);

		return $viewModel;
	}

	public function detailsAction() 
	{
		$container = new Container('receive_inventory');
		$viewModel = new ViewModel();

		if(!$container->id) {
			return $this->redirect()->toRoute('process/receive_inventory');
		}

		$receiveInventory = $this->getReceiveInventoryTable()->get($container->id);
		$form = $this->getServiceLocator()->get('Process\Form\DetailsReceiveInventoryForm');

		$detailsReceiveInventory = new DetailsReceiveInventory();
		$form->setInputFilter($detailsReceiveInventory->getInputFilter());
		
		$request = $this->getRequest();
		$data = $request->getPost()->toArray();
		$form->setData($data);


		if ($request->isPost()) {

			$serials = array();

			$qty = (int) $data['qty'];
			$serialValuesElementErrors = true;

			for($i = 0; $i < $qty; $i++) {
				$serialValue = $data['serials'][$i][0];
				if(isset($serialValue) && !empty($serialValue)) {
					$serials[$i] = array_filter($data['serials'][$i]);
				}
				else {
					$serialValuesElementErrors = false ;
					break;
				}
			}


			if ($form->isValid() && $serialValuesElementErrors) {

				$fileService = $this->getServiceLocator()->get('Admin\Service\FileService');

				$fileService->setDestination($this->config['component']['detail_receive_inventory']['file_path']);
				$fileService->setSize($this->config['file_characteristics']['file']['size']);
				$fileService->setExtension($this->config['file_characteristics']['file']['extension']);

				$manifestFile = $fileService->copy($this->params()->fromFiles('manifest_file'));

				$data['cost'] = str_replace('.','',$data['cost']);
				$data['receive_inventory'] = $container->id ;
				$data['serials'] = json_encode($serials);
				$data['manifest_file'] = $manifestFile ? $manifestFile : "" ;

				$detailsReceiveInventory->exchangeArray($data);
				$receiveInventoryId = $this->getDetailsReceiveInventoryTable()->save($detailsReceiveInventory);

				return $this->redirect()->toRoute('process/receive_inventory/add/details');


			}
			else {
				if(!$serialValuesElementErrors) {
					$serialValuesMessageError = "debe ingresar el serial principal para cada producto";
					$viewModel->setVariable('serialValuesMessageError', $serialValuesMessageError);
				}
			}
		}

		$detailsReceiveInventory = $this->getDetailsReceiveInventoryTable()->geList($container->id);

		$viewModel->setVariable('form',$form);
		$viewModel->setVariable('receiveInventory', $receiveInventory);
		$viewModel->setVariable('config', $this->config);
		$viewModel->setVariable('detailsReceiveInventory', $detailsReceiveInventory);

		$viewModel->setTemplate("process/receive-inventory/details");

		return $viewModel;

	}

	public function finishAction()
	{
		$container = new Container('receive_inventory');
		$container->getManager()->getStorage()->clear('receive_inventory');
		
		return $this->redirect()->toRoute('process/receive_inventory/add');
	}


	public function setConfig($config)
	{
		$this->config = $config;
	}
}