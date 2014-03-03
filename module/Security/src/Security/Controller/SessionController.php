<?php
namespace Security\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Security\Model\Login;
use Security\Form\LoginForm;

use Zend\Authentication\AuthenticationService;

use Security\Traits\SecurityTrait;

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

				/*@todo pendiente obtener valores enviados*/

				$authSessionAdapter = $this->getAuthSessionAdapter();
				$authentication = $authSessionAdapter->authenticate("kalelc","123456");

				if(is_object($authentication)) {
					return $this->redirect()->toRoute('product');
				}
				else
					dumpx($authentication,"es un mensaje");
			}
		}
		return $viewModel;
	}

	public function logoutAction()
	{
		$this->getAuthSessionAdapter()->clearIdentity();
		return $this->redirect()->toRoute('security/login');

	}
}