<?php
namespace Security\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Security\Model\Login;
use Security\Form\LoginForm;
use Security\Traits\SecurityTrait;
use Security\Traits\AuthenticationTrait;

use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;
use Application\ConfigAwareInterface;
use Zend\Authentication\AuthenticationService;

class SessionController extends AbstractActionController
implements ConfigAwareInterface
{
	use SecurityTrait,AuthenticationTrait;
	private $config;

	public function indexAction()
	{
		$config = $this->config;
		$routes = $config['router']['routes'];

		foreach($routes['process']['child_routes'] as $key => $route) {
			echo $key;
			echo "<br>" ;
		}
		dumpx($this->getRequest()->getRequestUri(),"current uri");
		exit();
	}

	public function loginAction()
	{

		$authenticationService = new AuthenticationService();

		if($authenticationService->hasIdentity())
			return $this->redirect()->toRoute('dashboard');

		$form = new LoginForm();

		$viewModel = new ViewModel();
		$this->layout("layout/login");

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

					$userObject = $authenticationService->getStorage()->read();
					$rol = $userObject->rol;
					$acl = new Acl();
					$acl->addResource(new Resource("dashboard"));

					if($rol==1) {
						$resources = $this->config['resources'];

						foreach($resources as $module => $resource) {
							foreach($resource as $resourceValue) {
								$acl->addResource(new Resource($resourceValue));
							}
						}
					}
					else {
						$acl->addRole(new Role($rol));

						$modules = $this->getModuleRolTable()->fetchAll($rol);

						foreach($modules as $module) {
							$acl->addResource(new Resource($module));
						}

					}
					$userObject->acl = serialize($acl);
					return $this->redirect()->toRoute('dashboard');
				}
				else {
					$form->get('username')->setValue("");
					$form->get('password')->setValue("");

					if($authSessionAdapter->getCode()==-5)
						$form->get("username")->setMessages(array('username' => $this->config['authentication_codes'][$authSessionAdapter->getCode()]));
					else
						$form->get("username")->setMessages(array('username' => $this->config['authentication_codes'][-6]));
				}
			}
			else {
				$form->get("username")->setMessages(array('username' => $this->config['authentication_codes'][-6]));

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

	public function setConfig($config)
	{
		$this->config = $config;
	}
}