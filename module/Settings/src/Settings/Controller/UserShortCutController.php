<?php
namespace Settings\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Settings\Model\UserShortCut;
use Settings\Form\UserShortCutForm;
use Settings\Traits\SettingsTrait;
use Application\ConfigAwareInterface;

class UserShortCutController extends AbstractActionController
implements ConfigAwareInterface
{
	use SettingsTrait;
	
	private $config;

	public function indexAction()
	{
		$viewModel = new ViewModel();
		$viewModel->setTemplate("settings/user-shortcut/index");
		$viewModel->setVariable("userShortCuts",$this->getUserShortCutTable()->fetchAll());
		$viewModel->setVariable("config",$this->config);
		return $viewModel;
	}

	public function addAction()
	{
		$viewModel = new ViewModel();
		$viewModel->setTemplate("settings/user-shortcut/add");
		
		$form = $this->getUserShortCutForm();

		$request = $this->getRequest();
		if ($request->isPost()) {
			$userShortCut = new UserShortCut();
			$form->setInputFilter($userShortCut->getInputFilter());
			$form->setData($request->getPost());

			if ($form->isValid()) {

				$userShortCut->exchangeArray($form->getData());
				$this->getUserShortCutTable()->save($userShortCut);

				return $this->redirect()->toRoute('settings/user-shortcut');
			}
		}
		$viewModel->setVariable('config',$this->config);
		$viewModel->setVariable('form',$form);

		return $viewModel;
	}


	public function editAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('settings/user-shortcut', array(
					'action' => 'add'
			));
		}
		$userShortCut = $this->getUserShortCutTable()->get($id);

		$form  = new UserShortCutForm();
		$form->bind($userShortCut);
		
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setInputFilter($userShortCut->getInputFilter());
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$this->getUserShortCutTable()->save($form->getData());

				return $this->redirect()->toRoute('settings/user-shortcut');
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
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('settings/user-shortcut');
		}
		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost('del', 'No');
			if ($del == 'Si') {
				$id = (int) $request->getPost('id');

				$this->getUserShortCutTable()->delete($id);
			}

			return $this->redirect()->toRoute('settings/user-shortcut');
		}
		return array(
				'id'=> $id,
				'rol' => $this->getUserShortCutTable()->get($id),
				'config' => $this->config,
		);
	}

	public function setConfig($config)
	{
		$this->config = $config;
	}
}