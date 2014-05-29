<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Model\UserType;
use Admin\Form\UserTypeForm;
use Admin\Traits\ModuleTablesTrait as AdminTablesTrait;
use Application\ConfigAwareInterface;

class UserTypeController extends AbstractActionController
implements ConfigAwareInterface
{
	use AdminTablesTrait;
	private $config;

	public function indexAction()
	{
		return new ViewModel(array(
			'userTypes' => $this->getUserTypeTable()->fetchAll(),
			'config' => $this->config,
			));
	}

	public function addAction()
	{
		$form = new UserTypeForm();

		$request = $this->getRequest();
		if ($request->isPost()) {
			$userType = new UserType();
			$form->setInputFilter($userType->getInputFilter());
			$form->setData($request->getPost());

			if ($form->isValid()) {

				$userType->exchangeArray($form->getData());
				$this->getUserTypeTable()->save($userType);

				return $this->redirect()->toRoute('admin/user_type');
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
			return $this->redirect()->toRoute('admin/user_type', array(
				'action' => 'add'
				));
		}
		$userType = $this->getUserTypeTable()->get($id);

		$form  = new UserTypeForm();
		$form->bind($userType);
		
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setInputFilter($userType->getInputFilter());
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$this->getUserTypeTable()->save($form->getData());

				return $this->redirect()->toRoute('admin/user_type');
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
			return $this->redirect()->toRoute('admin/user_type');
		}
		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost('del', 'No');
			if ($del == 'Si') {
				$id = (int) $request->getPost('id');
				$result = $this->getUserTypeTable()->delete($id);

				if(isset($result) && $result) {
					return $this->redirect()->toRoute('admin/user_type');
				}
				else {
					$viewModel->setVariable("error",true);
				}
			}
			else
				return $this->redirect()->toRoute('admin/user_type');
		}
		$viewModel->setVariables(array(
			'id'=> $id,
			'bank' => $this->getUserTypeTable()->get($id),
			'config' => $this->config,
			));

		return $viewModel;
	}

	public function setConfig($config)
	{
		$this->config = $config;
	}
}