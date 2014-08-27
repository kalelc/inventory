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
use Application\ConfigAwareInterface;

class SessionController extends AbstractActionController
implements ConfigAwareInterface
{
	use SecurityTrait;
	private $config;

	public function indexAction()
	{
		$this->getEventManager()->trigger("log.save", $this);
		dumpx("exit");

	}

	public function loginAction()
	{
		$form = new LoginForm();

		$this->layout("layout/security");

		$viewModel = new ViewModel();
		$viewModel->setVariable("form",$form);
		$viewModel->setVariable("config",$this->config);

		$request = $this->getRequest();
		if($request->isPost()) {

			$login = new Login();
            $login->getInputFilter()->get('captcha')->setRequired(false);

			$form->setInputFilter($login->getInputFilter());
			$form->setData($request->getPost());

			if ($form->isValid()){

				$username = $form->get('username')->getValue();
				$password = $form->get('password')->getValue();

				$authSessionAdapter = $this->getAuthSessionAdapter();
				if($authSessionAdapter->authenticate($username,$password)) {
				return $this->redirect()->toRoute('admin/bank');
				}
				else {
					$form->get('username')->setValue("");
					$form->get('password')->setValue("");
					$form->get("username")->setMessages(array('username' => "La dirección de correo electrónico o la contraseña que introducido no es correcta."));
				}
			}
		}
		return $viewModel;
	}

	public function logoutAction()
	{
		$authenticationService = new AuthenticationService();
		$authenticationService->clearIdentity();
		return $this->redirect()->toRoute('security/login');
	}

	public function aclAction()
	{
		$acl = new Acl();
		$rol = "admin";
		$acl->addRole(new Role($rol));
		$acl->addResource(new Resource('product'));
		$acl->addResource(new Resource('bank'));
		$acl->allow($rol, 'product', array('read','create'));
		$acl->allow($rol, 'bank', array('read','create'));

		//dump($acl->isAllowed($rol, 'product','create'));
		dump($acl->getResources(),"getResources()");
		dumpx($acl->getRoles(),"getRoles()");

	}

	public function setConfig($config)
	{
		$this->config = $config;
	}
}