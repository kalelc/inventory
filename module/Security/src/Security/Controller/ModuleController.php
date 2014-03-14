<?php
namespace Security\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Security\Model\Module;
use Security\Form\ModuleForm;
use Security\Traits\ModuleTablesTrait as SecurityTablesTrait;
use Application\ConfigAwareInterface;

class ModuleController extends AbstractActionController
implements ConfigAwareInterface
{
	use SecurityTablesTrait;
	private $config;

	public function indexAction()
	{
		return new ViewModel(array(
				'modules' => $this->getModuleTable()->fetchAll(),
				'config' => $this->config,
		));
	}

	public function addAction()
	{
		$form = new ModuleForm();

		$request = $this->getRequest();
		if ($request->isPost()) {
			$module = new Module();
			$form->setInputFilter($module->getInputFilter());
			$form->setData($request->getPost());

			if ($form->isValid()) {

				$module->exchangeArray($form->getData());
				$this->getModuleTable()->save($module);

				return $this->redirect()->toRoute('admin/module');
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
			return $this->redirect()->toRoute('admin/module', array(
					'action' => 'add'
			));
		}
		$module = $this->getModuleTable()->get($id);

		$form  = new ModuleForm();
		$form->bind($module);
		
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setInputFilter($module->getInputFilter());
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$this->getModuleTable()->save($form->getData());

				return $this->redirect()->toRoute('admin/module');
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
			return $this->redirect()->toRoute('admin/module');
		}
		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost('del', 'No');
			if ($del == 'Si') {
				$id = (int) $request->getPost('id');

				$this->getModuleTable()->delete($id);
			}

			return $this->redirect()->toRoute('admin/module');
		}
		return array(
				'id'=> $id,
				'module' => $this->getModuleTable()->get($id),
				'config' => $this->config,
		);
	}

	public function setConfig($config)
	{
		$this->config = $config;
	}
}