<?php
namespace Process\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Process\Model\ReceiveInventory;
use Process\Model\ReceiveInventoryTable;
use Process\Model\DetailsReceiveInventory;
use Process\Model\ProductsReceiveInventory;
use Zend\View\Model\JsonModel;
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
use Zend\Authentication\AuthenticationService;

class ReceiveInventoryController extends AbstractActionController
implements ConfigAwareInterface
{
	use ProcessTablesTrait, AdminTablesTrait;
	private $config;

	public function indexAction()
	{
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

				$authenticationService = new AuthenticationService();
				$user = $authenticationService->getStorage()->read()->id;

				$receiveInventory->setUser($user);
				$receiveInventory->exchangeArray($data);

				$receiveInventoryId = $this->getReceiveInventoryTable()->save($receiveInventory);

				$container = new Container('receive_inventory');
				$container->id = $receiveInventoryId;
				$container->user = $user;


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

		$receiveInventoryId = $container->id ;
		$user = $container->user ;

		$receiveInventory = $this->getReceiveInventoryTable()->get($receiveInventoryId,$user);
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

				$fileService->setDestination($this->config['component']['details_receive_inventory']['file_path']);
				$fileService->setSize($this->config['file_characteristics']['file']['size']);
				$fileService->setExtension($this->config['file_characteristics']['file']['extension']);

				$manifestFile = $fileService->copy($this->params()->fromFiles('manifest_file'));

				$data['cost'] = str_replace('.','',$data['cost']);
				$data['receive_inventory'] = $container->id ;
				$data['serials'] = json_encode($serials);
				$data['manifest_file'] = $manifestFile ? $manifestFile : "" ;

				$detailsReceiveInventory->exchangeArray($data);
				$detailsReceiveInventoryId = $this->getDetailsReceiveInventoryTable()->save($detailsReceiveInventory);

				$productsReceiveInventory = new ProductsReceiveInventory();
				$productsReceiveInventory->setDetailsReceiveInventory($detailsReceiveInventoryId);
				$productsReceiveInventory->setSerial($serials);

				$this->getProductsReceiveInventoryTable()->save($productsReceiveInventory);

				return $this->redirect()->toRoute('process/receive_inventory/add/details');
			}
			else {
				if(!$serialValuesElementErrors) {
					$serialValuesMessageError = "debe ingresar el serial principal para cada producto";
					$viewModel->setVariable('serialValuesMessageError', $serialValuesMessageError);
				}
			}
		}

		$detailsReceiveInventory = $this->getDetailsReceiveInventoryTable()->get($container->id);
		$detailReceiveInventoryList = array();

		foreach($detailsReceiveInventory as $detailReceiveInventory) {

			$productsReceiveInventory = $this->getProductsReceiveInventoryTable()->getSerialList($detailReceiveInventory->getId());
			
			$serials = array();	

			foreach($productsReceiveInventory as $productReceiveInventory) {
				$serials[] = $productReceiveInventory->getSerial();
				$detailReceiveInventory->setQty($productReceiveInventory->getNumber());
			}
			$detailReceiveInventory->setSerials($serials);
			$detailReceiveInventoryList[] = $detailReceiveInventory;

		}

		//dumpx($detailReceiveInventoryList);
		
		$viewModel->setVariable('form',$form);
		$viewModel->setVariable('receiveInventory', $receiveInventory);
		$viewModel->setVariable('config', $this->config);
		$viewModel->setVariable('detailReceiveInventoryList', $detailReceiveInventoryList);

		$viewModel->setTemplate("process/receive-inventory/details");

		return $viewModel;

	}

	public function finishAction()
	{	
		$viewModel = new ViewModel();
		$viewModel->setTemplate("process/receive-inventory/finish-detail");
		$viewModel->setVariable('config',$this->config);

		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost('del', 'No');
			if ($del == 'Si') {
				$container = new Container('receive_inventory');
				$container->getManager()->getStorage()->clear('receive_inventory');
				return $this->redirect()->toRoute('process/receive_inventory/add');
			}
			else {
				return $this->redirect()->toRoute('process/receive_inventory/add/details');
			}
		}
		return $viewModel;
	}

	public function deleteDetailAction()
	{
		$viewModel = new ViewModel();
		$container = new Container('receive_inventory');
		$receiveInventoryId = (int) $container->id;

		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id || !$receiveInventoryId) {
			return $this->redirect()->toRoute('process/receive_inventory/add/details');
		}

		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost('del', 'No');
			if ($del == 'Si') {
				$id = (int) $request->getPost('id');
				@unlink($this->config['component']['detail_receive_inventory']['file_path']."/".$this->getDetailsReceiveInventoryTable()->get($receiveInventoryId,$id)->getManifestFile());

				$result = $this->getProductsReceiveInventoryTable()->delete($id);
				$result = $this->getDetailsReceiveInventoryTable()->delete($id);

				if(isset($result) && $result) {
					return $this->redirect()->toRoute('process/receive_inventory/add/details');
				}
				else {
					$viewModel->setVariable("error",true);
				}
			}
			else
				return $this->redirect()->toRoute('process/receive_inventory/add/details');
		}

		$viewModel->setVariables(array(
			'id'=> $id,
			'detailReceiveInventory' => $this->getDetailsReceiveInventoryTable()->get($receiveInventoryId,$id),
			'config' => $this->config,
			));

		return $viewModel;
	}

	public function searchSerialAction()
	{
		$serialValue = $this->params()->fromPost('serial');

		if(isset($serialValue) && !empty($serialValue)) {

			$serials = $this->getDetailsReceiveInventoryTable()->searchSerial($serialValue);

			$serialList = array();
			
			foreach($serials as $serial) {
				$serialList[] = $serial->getSerials();
			}

			$jsonModel = new JsonModel();
			$jsonModel->setVariable("serials",$serialList);
			return $jsonModel;
		}
	}

	public function setConfig($config)
	{
		$this->config = $config;
	}
}