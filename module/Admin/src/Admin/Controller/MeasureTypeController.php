<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Model\MeasureType;
use Admin\Form\MeasureTypeForm;
use Admin\Traits\ModuleTablesTrait as AdminTablesTrait;
use Application\ConfigAwareInterface;

class MeasureTypeController extends AbstractActionController
implements ConfigAwareInterface
{
	use AdminTablesTrait;
	private $config;

	public function indexAction()
	{
		return new ViewModel(array(
			'measuresTypes' => $this->getMeasureTypeTable()->fetchAll(),
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

				return $this->redirect()->toRoute('measure_type');
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
			return $this->redirect()->toRoute('measure_type', array(
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

				return $this->redirect()->toRoute('measure_type');
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
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('measure_type');
		}
		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost('del', 'No');
			if ($del == 'Si') {
				$id = (int) $request->getPost('id');

				$this->getMeasureTypeTable()->delete($id);
			}

			return $this->redirect()->toRoute('measure_type');
		}
		return array(
			'id'=> $id,
			'measureType' => $this->getMeasureTypeTable()->get($id),
			'config' => $this->config,
			);
	}

	public function setConfig($config)
	{
		$this->config = $config;
	}
}