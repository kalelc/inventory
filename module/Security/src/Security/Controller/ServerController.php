<?php
namespace Security\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Traits\ModuleTablesTrait as AdminTablesTrait;
use Application\ConfigAwareInterface;
use Security\Form\LoginForm;
use Security\Model\Login;

class ServerController extends AbstractActionController
implements ConfigAwareInterface
{
	private $config;

	public function indexAction()
	{

	}
	public function loginAction()
	{
		$viewModel = new ViewModel();
		$form = new LoginForm();
		$viewModel->setVariable('form',$form);
		$viewModel->setVariable('config',$this->config);

		$request = $this->getRequest();
		if($request->isPost()) {

			$login = new Login();

			$form->setInputFilter($login->getInputFilter());
			$form->setData($request->getPost());

			if ($form->isValid()) {
				dumpx("form es valido");
			}

		}
		return $viewModel;
	}
	public function logoutAction()
	{
	}
	public function recoveryPasswordAction()
	{
	}

	public function setConfig($config)
	{
		$this->config = $config;
	}
}