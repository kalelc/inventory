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
                $container->receiveInventoryId = $receiveInventoryId;

                //dump($container);
                //dumpx($container->receiveInventoryId);
                $form = $this->getServiceLocator()->get('Process\Form\DetailsReceiveInventoryForm');
                $viewModel->setVariable('form',$form);
				
				$viewModel->setTemplate("process/receive-inventory/details");
				//return $this->redirect()->toRoute('process/receive_inventory');
			}
		}
		$viewModel->setVariable('config', $this->config);

		return $viewModel;
	}

	public function setConfig($config){
		$this->config = $config;
	}
}