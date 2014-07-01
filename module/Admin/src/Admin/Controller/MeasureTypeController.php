<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Model\MeasureType;
use Admin\Form\MeasureTypeForm;
use Admin\Traits\ModuleTablesTrait as AdminTablesTrait;
use Application\ConfigAwareInterface;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Iterator as PaginatorIterator;

class MeasureTypeController extends AbstractActionController
implements ConfigAwareInterface
{
	use AdminTablesTrait;
	private $config;

	public function indexAction()
	{
		$page = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : 1;

		$measuresType = $this->getMeasureTypeTable()->fetchAll();
		$paginator = new Paginator(new PaginatorIterator($measuresType));
		$paginator->setCurrentPageNumber($page)
		->setItemCountPerPage($this->config['pagination']['itempage'])
		->setPageRange($this->config['pagination']['pagerange']);

		return new ViewModel(array(
			'measuresTypes' => $paginator,
			'config' => $this->config,
			));
	}

	public function addAction()
	{
		$form = new MeasureTypeForm();
		$form->get('submit')->setValue('guardar');

		$request = $this->getRequest();
		if ($request->isPost()) {
			$MeasureType = new MeasureType();
			$form->setInputFilter($MeasureType->getInputFilter());
			$form->setData($request->getPost());

			if ($form->isValid()) {

				$MeasureType->exchangeArray($form->getData());
				$this->getMeasureTypeTable()->save($MeasureType);

				return $this->redirect()->toRoute('admin/measure_type');
			}
		}
		return array('form' => $form,
			'config' => $this->config,
			);
	}


	public function editAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('admin/measure_type', array(
				'action' => 'add'
				));
		}
		$MeasureType = $this->getMeasureTypeTable()->get($id);

		$form  = new MeasureTypeForm();
		$form->bind($MeasureType);
		$form->get('submit')->setAttribute('value', 'Editar');

		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setInputFilter($MeasureType->getInputFilter());
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$this->getMeasureTypeTable()->save($form->getData());

				return $this->redirect()->toRoute('admin/measure_type');
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
			return $this->redirect()->toRoute('admin/measure_type');
		}
		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost('del', 'No');
			if ($del == 'Si') {
				$id = (int) $request->getPost('id');

				$result = $this->getMeasureTypeTable()->delete($id);

				if(isset($result) && $result) {
					return $this->redirect()->toRoute('admin/measure_type');
				}
				else {
					$viewModel->setVariable("error",true);
				}
			}
			else
				return $this->redirect()->toRoute('admin/measure_type');
		}
		$viewModel->setViariables(array(
			'id'=> $id,
			'measureType' => $this->getMeasureTypeTable()->get($id),
			'config' => $this->config,
			));

		return $viewModel;
	}

	public function setConfig($config)
	{
		$this->config = $config;
	}
}