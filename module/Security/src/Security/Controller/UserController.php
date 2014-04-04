<?php
namespace Security\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Security\Model\User;
use Security\Form\UserForm;
use Security\Traits\SecurityTrait;
use Application\ConfigAwareInterface;

use Zend\Validator\File\Extension as FileExtension;
use Zend\Validator\File\Size as FileSize;

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

			$user = new User();
			$adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
			$user->setAdapter($adapter);

			$form->setInputFilter($user->getInputFilter());
			$form->setData($request->getPost());

			$fileService = $this->getServiceLocator()->get('Admin\Service\FileService');

			$picture = $fileService->copy($this->params()->fromFiles('picture'));
			$signature = $fileService->copy($this->params()->fromFiles('signature'));

			$fileExtension = new FileExtension($this->config['file_characteristics']['image']['extension']);
			$fileSize = new FileSize($this->config['file_characteristics']['image']['size']);

			//pendiente upload image
			if(!empty($this->params()->fromFiles('signature'))) {
				$fileExtension->isValid($this->params()->fromFiles('signature'));
			}
			if(!empty($this->params()->fromFiles('picture'))) {
				$fileSize->isValid($this->params()->fromFiles('picture'));
			}

			if ($form->isValid()) {
				$user->exchangeArray($form->getData());
				$this->getUserTable()->save($user);

				return $this->redirect()->toRoute('security/user');
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
			return $this->redirect()->toRoute('security/user', array('action' => 'add'));
		}
		$user = $this->getUserTable()->get($id);

		$form = $this->getServiceLocator()->get("Security\Form\UserForm");
		$adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
		$user->setAdapter($adapter);

		$form->bind($user);
		$form->get("first_name")->setValue($user->getFirstName());
		$form->get("last_name")->setValue($user->getLastName());


		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setInputFilter($user->getInputFilter());
			$form->setData($request->getPost());

			if ($form->isValid()) {
			//	dumpx($form->getData(),"form values");
				$this->getUserTable()->save($form->getData());

				return $this->redirect()->toRoute('security/user');
			}
			else
				dumpx($form->getMessages(),"no es valido");
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
			return $this->redirect()->toRoute('security/user');
		}
		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost('del', 'No');
			if ($del == 'Si') {
				$id = (int) $request->getPost('id');

				$this->getUserTable()->delete($id);
			}

			return $this->redirect()->toRoute('security/user');
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