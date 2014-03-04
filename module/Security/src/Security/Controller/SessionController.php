<?php
namespace Security\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\AuthenticationService;

use Security\Model\Login;
use Security\Form\LoginForm;
use Security\Traits\SecurityTrait;

use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

class SessionController extends AbstractActionController
{
	use SecurityTrait;

	public function indexAction()
	{
		dumpx($this->getAuthSessionAdapter()->getIdentity(),"SessionController::index");
	}

	public function loginAction()
	{
		$form = new LoginForm();

		$viewModel = new ViewModel();
		$viewModel->setVariable("form",$form);

		$request = $this->getRequest();
		if($request->isPost()) {

			$login = new Login();

			$form->setInputFilter($login->getInputFilter());
			$form->setData($request->getPost());

			if ($form->isValid()){

				$username = $form->get('username')->getValue();
				$password = $form->get('password')->getValue();

				$authSessionAdapter = $this->getAuthSessionAdapter();
				$authentication = $authSessionAdapter->authenticate($username,$password);

				if(is_object($authentication)) {

					/*search by rol*/
					
					/*crear lista de control de accesos*/
					return $this->redirect()->toRoute('product');
				}
				else {
					//@todo revisar mensajes
					$form->get("username")->setMessages(array('username' => "La dirección de correo electrónico o la contraseña que  introducido no es correcta."));
				}
			}
		}
		return $viewModel;
	}

	public function logoutAction()
	{
		$this->getAuthSessionAdapter()->clearIdentity();
		return $this->redirect()->toRoute('security/login');

	}

	public function aclAction()
	{

		$acl = new Acl();
		$username = "kalelc";
		$acl->addRole(new Role($username));
		$acl->addResource(new Resource('product'));
		$acl->addResource(new Resource('bank'));
		$acl->allow($username, 'product', array('read','create'));
		$acl->allow($username, 'bank', array('read','create'));

		dumpx($acl->isAllowed($username, 'product','delete'));



		dump($acl,"acl");
		dump($acl->getResources(),"getResources()");
		dumpx($acl->getRoles(),"getRoles()");

	}
}