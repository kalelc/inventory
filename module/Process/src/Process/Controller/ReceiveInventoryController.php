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
use Application\ConfigAwareInterface;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Iterator as PaginatorIterator;

class ReceiveInventoryController extends AbstractActionController
implements ConfigAwareInterface
{
	//use ProcessTablesTrait;
	private $config;

	public function indexAction()
	{
		dump(getenv('APPLICATION_ENV'));
		exit();
	}

	public function addAction()
	{
		$form = $this->getServiceLocator()->get("Admin\Form\ReceiveInventoryForm");
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

				dumpx($data);
				$this->getMeasureTable()->save($measure);

				return $this->redirect()->toRoute('admin/measure');
			}
		}
		return array(
			'form' => $form,
			'config' => $this->config
			);

	}

	public function setConfig($config){
		$this->config = $config;
	}
}