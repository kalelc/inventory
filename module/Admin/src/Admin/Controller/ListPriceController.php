<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Model\ListPrice;
use Admin\Form\ListPriceForm;
use Admin\Traits\ModuleTablesTrait as AdminTablesTrait;
use Application\ConfigAwareInterface;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Iterator as PaginatorIterator;

class ListPriceController extends AbstractActionController 
implements ConfigAwareInterface
{
	use AdminTablesTrait;
	private $config;

	public function indexAction()
	{
		$page = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : 1;

		$listPrice = $this->getListPriceTable()->fetchAll();
		$paginator = new Paginator(new PaginatorIterator($listPrice));
		$paginator->setCurrentPageNumber($page)
		->setItemCountPerPage($this->config['pagination']['itempage'])
		->setPageRange($this->config['pagination']['pagerange']);

		return new ViewModel(array(
			'listPrices' => $paginator,
			'config' => $this->config,
			));
	}

	public function addAction()
	{
		$form = new listPriceForm();

		$request = $this->getRequest();
		if ($request->isPost()) {
			$listPrice = new ListPrice();
			$form->setInputFilter($listPrice->getInputFilter());
			$form->setData($request->getPost());

			if ($form->isValid()) {

				$listPrice->exchangeArray($form->getData());
				$this->getListPriceTable()->save($listPrice);

				return $this->redirect()->toRoute('admin/list_price');
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
			return $this->redirect()->toRoute('admin/listPrice', array(
				'action' => 'add'
				));
		}
		$listPrice = $this->getListPriceTable()->get($id);

		$form  = new ListPriceForm();
		$form->bind($listPrice);
		
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setInputFilter($listPrice->getInputFilter());
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$this->getListPriceTable()->save($form->getData());

				return $this->redirect()->toRoute('admin/list_price');
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
			return $this->redirect()->toRoute('admin/list_price');
		}
		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost('del', 'No');
			if ($del == 'Si') {
				$id = (int) $request->getPost('id');

				$result = $this->getListPriceTable()->delete($id);

				if(isset($result) && $result) {
					return $this->redirect()->toRoute('admin/list_price');
				}
				else {
					$viewModel->setVariable("error",true);
				}
			}
			else
				return $this->redirect()->toRoute('admin/list_price');
		}
		$viewModel->setVariable(array(
			'id'=> $id,
			'listPrice' => $this->getListPriceTable()->get($id),
			'config' => $this->config,
			));

		return $viewModel;
	}

	public function setConfig($config)
	{
		$this->config = $config;
	}
}