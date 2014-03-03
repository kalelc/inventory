<?php
namespace Security\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Security\Model\Login;
use Security\Form\LoginForm;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;

class SessionController extends AbstractActionController
{
	public function indexAction()
	{

		$auth = new AuthenticationService();
		dumpx($auth->getIdentity());
	}

	public function loginAction()
	{
		/*$form = new LoginForm();

		$viewModel = new ViewModel();
		$viewModel->setVariable("form",$form);

		$request = $this->getRequest();
		
		if($request->isPost()) {

			$login = new Login();

			$form->setInputFilter($login->getInputFilter());
			$form->setData($request->getPost());

			if ($form->isValid()){*/

				$authSessionAdapter = $this->getServiceLocator()->get("Security\Adapter\AuthSessionAdapter");
				$authentication = $authSessionAdapter->authenticate("kalelc","123456");

				/*
				$authService = new AuthenticationService();
				$authService->setStorage(new SessionStorage(SessionStorage::NAMESPACE_DEFAULT));
				$authService->setAdapter($authSessionAdapter);
				$authService->authenticate();
				dumpx($authService->authenticate()->isValid());

				dumpx($authSessionAdapter);*/
				//dumpx($authService->getAdapter());

			//}
		//}

		//return $viewModel;
	}

	public function logoutAction()
	{
		$auth = new AuthenticationService();
		dumpx($auth->clearIdentity());
	}
}