<?php
namespace Process\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Process\Model\OutputInventory;
use Process\Model\OutputInventoryTable;
use Process\Model\DetailsOutputInventory;
use Process\Form\DetailsOutputInventoryForm;
use Zend\Validator\File\Size;
use Zend\Validator\File\Extension;
use Process\Form\OutputInventoryForm;
use Process\Traits\ModuleTablesTrait as ProcessTablesTrait;
use Admin\Traits\ModuleTablesTrait as AdminTablesTrait;
use Application\ConfigAwareInterface;
use Zend\Session\Container;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Iterator as PaginatorIterator;
use Zend\Authentication\AuthenticationService;

class OutputInventoryController extends AbstractActionController
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

		$form = $this->getServiceLocator()->get("Process\Form\OutputInventoryForm");
		$viewModel->setVariable('form', $form);

		$request = $this->getRequest();

		if ($request->isPost()) {

			$outputInventory = new OutputInventory();
			$form->setInputFilter($outputInventory->getInputFilter());

			$data = $request->getPost()->toArray();

			$form->setData($data);

			if ($form->isValid()) {

				$authenticationService = new AuthenticationService();
				$user = $authenticationService->getStorage()->read()->id;

				$outputInventory->setUser($user);
				$outputInventory->exchangeArray($data);

				$outputInventoryId = $this->getOutputInventoryTable()->save($outputInventory);

				$container = new Container('output_inventory');
				$container->id = $outputInventoryId;
				$container->user = $user;

				return $this->redirect()->toRoute('process/output_inventory/add/details');
			}
		}
		$viewModel->setVariable('config', $this->config);

		return $viewModel;
	}

	public function detailsAction() 
	{
		$container = new Container('output_inventory');
		$viewModel = new ViewModel();

		if(!$container->id) {
			return $this->redirect()->toRoute('process/output_inventory');
		}

		$outputInventoryId = $container->id ;
		$user = $container->user ;

		$outputInventory = $this->getOutputInventoryTable()->get($outputInventoryId,$user);
		$form = new DetailsOutputInventoryForm();

		$detailsOutputInventory = new DetailsOutputInventory();
		$form->setInputFilter($detailsOutputInventory->getInputFilter());
		
		$request = $this->getRequest();
		$data = $request->getPost()->toArray();
		$form->setData($data);


		if ($request->isPost()) {

			if ($form->isValid()) {

				$detailsOutputInventory->exchangeArray($data);
				$detailsOutputInventory->setOutputInventory($outputInventoryId);

				$this->getDetailsOutputInventoryTable()->save($detailsOutputInventory);

				return $this->redirect()->toRoute('process/output_inventory/add/details');
			}
		}

		$detailsOutputInventory = $this->getDetailsOutputInventoryTable()->get($container->id);

		$viewModel->setVariable('form',$form);
		$viewModel->setVariable('outputInventory', $outputInventory);
		$viewModel->setVariable('config', $this->config);
		$viewModel->setVariable('detailsOutputInventory', $detailsOutputInventory);

		$viewModel->setTemplate("process/output-inventory/details");

		return $viewModel;
	}

	public function finishAction()
	{
		$container = new Container('output_inventory');
		$container->getManager()->getStorage()->clear('output_inventory');
		
		return $this->redirect()->toRoute('process/output_inventory/add');
	}

	public function deleteDetailAction()
	{}


	public function setConfig($config)
	{
		$this->config = $config;
	}
}