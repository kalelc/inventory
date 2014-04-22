<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Model\SerialName;
use Admin\Form\SerialNameForm;
use Admin\Traits\ModuleTablesTrait as AdminTablesTrait;
use Application\ConfigAwareInterface;

class SerialNameController extends AbstractActionController
implements ConfigAwareInterface
{
	use AdminTablesTrait;
	private $config;

	public function indexAction()
	{
		return new ViewModel(array(
			'serialsName' => $this->getSerialNameTable()->fetchAll(),
			'config' => $this->config,
			));
	}

	public function addAction()
	{
		$form = new SerialNameForm();

		$request = $this->getRequest();
		if ($request->isPost()) {
			$serialName = new SerialName();
			$form->setInputFilter($serialName->getInputFilter());
			$form->setData($request->getPost());

			if ($form->isValid()) {

				$serialName->exchangeArray($form->getData());
				$this->getSerialNameTable()->save($serialName);

				return $this->redirect()->toRoute('admin/serial_name');
			}
		}
		return array(
			'form' => $form,
			'config' => $this->config,
			);
	}


	public function editAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('admin/serial_name', array(
				'action' => 'add'
				));
		}
		$serialName = $this->getSerialNameTable()->get($id);

		$form  = new SerialNameForm();
		$form->bind($serialName);
		
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setInputFilter($serialName->getInputFilter());
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$this->getSerialNameTable()->save($form->getData());

				return $this->redirect()->toRoute('admin/serial_name');
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
			return $this->redirect()->toRoute('admin/serial_name');
		}
		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost('del', 'No');
			if ($del == 'Si') {
				$id = (int) $request->getPost('id');

				$result = $this->getSerialNameTable()->delete($id);

				if(isset($result) && $result) {
					return $this->redirect()->toRoute('admin/serial_name');
				}
				else {
					$viewModel->setVariable("error",true);
				}
			}
			else
				return $this->redirect()->toRoute('admin/serial_name');

		}
		$viewModel->setVariables(array(
			'id'=> $id,
			'serialName' => $this->getSerialNameTable()->get($id),
			'config' => $this->config,
			));

		return $viewModel;
	}

	public function setConfig($config)
	{
		$this->config = $config;
	}
}