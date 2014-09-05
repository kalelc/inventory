<?php
namespace Security\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Security\Model\Rol;
use Security\Form\RolForm;
use Security\Traits\SecurityTrait;
use Application\ConfigAwareInterface;

use Zend\Authentication\AuthenticationService;

class RolController extends AbstractActionController
implements ConfigAwareInterface
{
	use SecurityTrait;
	
	private $config;

	public function indexAction()
	{
		$authenticationService = new AuthenticationService();
		
		if(!$authenticationService->hasIdentity())
			return $this->redirect()->toRoute('security/login');
		
		return new ViewModel(array(
			'roles' => $this->getRolTable()->fetchAll(),
			'config' => $this->config,
			));
	}

	public function addAction()
	{
		$authenticationService = new AuthenticationService();
		
		if(!$authenticationService->hasIdentity())
			return $this->redirect()->toRoute('security/login');

		$form = new RolForm();
		$resources = $this->config['resources'];

		$request = $this->getRequest();
		if ($request->isPost()) {
			$rol = new Rol();
			$form->setInputFilter($rol->getInputFilter());

			$resources = $request->getPost('resources');
			$form->setData($request->getPost());

			if ($form->isValid() && count($resources)) {
				$rol->exchangeArray($form->getData());
				$rolId = $this->getRolTable()->save($rol);

				$this->getModuleRolTable()->save($rolId,$resources);
				
				return $this->redirect()->toRoute('security/rol');
			}
			else {
				$form->get("resources")->setMessages(array('resources' => "para crear un rol debe asignar permisos"));
			}

		}
		return array(
			'form' => $form,
			'resources' => $resources,
			'config' => $this->config
			);
	}


	public function editAction()
	{
		$authenticationService = new AuthenticationService();
		
		if(!$authenticationService->hasIdentity())
			return $this->redirect()->toRoute('security/login');

		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('security/rol', array(
				'action' => 'add'
				));
		}
		
		$rol = $this->getRolTable()->get($id);
		$modulesRoles = $this->getModuleRolTable()->fetchAll($id);
		$resources = $this->config['resources'];

		$form  = new RolForm();
		$form->bind($rol);
		
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setInputFilter($rol->getInputFilter());
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$this->getRolTable()->save($form->getData());

				return $this->redirect()->toRoute('security/rol');
			}
		}
		return array(
			'id' => $id,
			'form' => $form,
			'config' => $this->config,
			'resources' => $resources,
			'modulesRoles' => $modulesRoles,
			);
	}

	public function deleteAction()
	{
		$authenticationService = new AuthenticationService();
		
		if(!$authenticationService->hasIdentity())
			return $this->redirect()->toRoute('security/login');
		
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('security/rol');
		}
		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost('del', 'No');
			if ($del == 'Si') {
				$id = (int) $request->getPost('id');

				$result = $this->getRolTable()->delete($id);
				if(isset($result) && $result) {
					return $this->redirect()->toRoute('security/rol');
				}
				else {
					$viewModel->setVariable("error",true);
				}
			}

			return $this->redirect()->toRoute('security/rol');
		}
		return array(
			'id'=> $id,
			'rol' => $this->getRolTable()->get($id),
			'config' => $this->config,
			);
	}

	public function setConfig($config)
	{
		$this->config = $config;
	}
}