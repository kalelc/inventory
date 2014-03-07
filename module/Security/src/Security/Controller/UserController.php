<?php
namespace Security\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Security\Model\User;
use Security\Form\UserForm;
use Security\Traits\SecurityTrait;
use Application\ConfigAwareInterface;

class UserController extends AbstractActionController
implements ConfigAwareInterface
{
	use SecurityTrait;
	
	private $config;

	public function indexAction()
	{
		return new ViewModel(array(
				'users' => $this->getUserTable()->fetchAll(),
				'config' => $this->config,
		));
	}

	public function addAction()
	{
		$form = $this->getServiceLocator()->get("Security\Form\UserForm");

		$request = $this->getRequest();
		if ($request->isPost()) {

			$adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
			$user = new User();
			$user->setAdapter($adapter);

			$form->setInputFilter($user->getInputFilter());
			$form->setData($request->getPost());

			if ($form->isValid()) {

				$user->exchangeArray($form->getData());
				$this->getUserTable()->save($user);

				return $this->redirect()->toRoute('admin/user');
			}
		}
		return array(
				'form' => $form,
				'config' => $this->config);
	}


	public function editAction()
	{}

	public function deleteAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('admin/user');
		}
		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost('del', 'No');
			if ($del == 'Si') {
				$id = (int) $request->getPost('id');

				$this->getUserTable()->delete($id);
			}

			return $this->redirect()->toRoute('admin/user');
		}
		return array(
				'id'=> $id,
				'user' => $this->getUserTable()->get($id),
				'config' => $this->config,
		);
	}

	public function setConfig($config)
	{
		$this->config = $config;
	}
}