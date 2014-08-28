<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Model\Bank;
use Admin\Form\BankForm;
use Admin\Traits\ModuleTablesTrait as AdminTablesTrait;
use Application\ConfigAwareInterface;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Iterator as PaginatorIterator;

class BankController extends AbstractActionController implements ConfigAwareInterface
{
	use AdminTablesTrait;
	private $config;

	public function indexAction()
	{	
		$page = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : 1;

		$banks = $this->getBankTable()->fetchAll();
		$paginator = new Paginator(new PaginatorIterator($banks));
		$paginator->setCurrentPageNumber($page)
		->setItemCountPerPage($this->config['pagination']['itempage'])
		->setPageRange($this->config['pagination']['pagerange']);
		
		return new ViewModel(array(
			'banks' => $paginator,
			'config' => $this->config,
			));
	}

	public function addAction()
	{
		$form = new BankForm();

		$request = $this->getRequest();
		if ($request->isPost()) {
			$bank = new Bank();
			$form->setInputFilter($bank->getInputFilter());
			$form->setData($request->getPost());

			if ($form->isValid()) {

				$bank->exchangeArray($form->getData());
				$result = $this->getBankTable()->save($bank);
				return $this->redirect()->toRoute('admin/bank');
			}
		}
		return array(
			'form' => $form,
			'config' => $this->config);
	}


	public function editAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('admin/bank', array(
				'action' => 'add'
				));
		}
		$bank = $this->getBankTable()->get($id);

		$form  = new BankForm();
		$form->bind($bank);
		
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setInputFilter($bank->getInputFilter());
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$this->getBankTable()->save($form->getData());

				return $this->redirect()->toRoute('admin/bank');
			}
		}
		return array(
			'id' => $id,
			'form' => $form,
			'config' => $this->config,
			);
	}

	public function deleteAction()
	{
		$viewModel = new ViewModel();

		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('admin/bank');
		}
		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost('del', 'No');
			if ($del == 'Si') {
				$id = (int) $request->getPost('id');
				$result = $this->getBankTable()->delete($id);

				if(isset($result) && $result) {
					return $this->redirect()->toRoute('admin/bank');
				}
				else {
					$viewModel->setVariable("error",true);
				}
			}
			else
				return $this->redirect()->toRoute('admin/bank');
		}
		$viewModel->setVariables(array(
			'id'=> $id,
			'bank' => $this->getBankTable()->get($id),
			'config' => $this->config,
			));

		return $viewModel;
	}

	public function setConfig($config)
	{
		$this->config = $config;
	}
}
