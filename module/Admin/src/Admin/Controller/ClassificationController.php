<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\File\Transfer\Adapter\Http  as HttpTransfer;
use Zend\Validator\File\Size;
use Zend\Validator\File\Extension;
use Admin\Model\Classification;
use Admin\Form\ClassificationForm;
use Admin\Traits\ModuleTablesTrait as AdminTablesTrait;
use Application\ConfigAwareInterface;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Iterator as PaginatorIterator;

class ClassificationController extends AbstractActionController
implements ConfigAwareInterface
{
	use AdminTablesTrait;
	private $config;
	
	public function indexAction()
	{
		$page = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : 1;

		$classification = $this->getClassificationTable()->fetchAll();
		$paginator = new Paginator(new PaginatorIterator($classification));
		$paginator->setCurrentPageNumber($page)
		->setItemCountPerPage($this->config['pagination']['itempage'])
		->setPageRange($this->config['pagination']['pagerange']);

		return new ViewModel(array(
			'classifications' => $paginator,
			'config' => $this->config
			));
	}

	public function addAction()
	{
		$form = $this->getServiceLocator()->get("Admin\Form\ClassificationForm");
		$request = $this->getRequest();

		if ($request->isPost()) {

			$classification = new Classification();

			$form->setInputFilter($classification->getInputFilter());
			$data = $request->getPost()->toArray();

			$form->setData($data);

			if ($form->isValid()) {

				$classification->exchangeArray($data);
				$this->getClassificationTable()->save($classification);

				return $this->redirect()->toRoute('admin/classification');
			}
		}
		return array(
			'form' => $form,
			'config' => $this->config
			);
	}

	public function editAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('admin/classification', array(
				'action' => 'add'
				));
		}
		$classification = $this->getClassificationTable()->get($id);

		$form = $this->getServiceLocator()->get("Admin\Form\ClassificationForm");
		
		$form->get("name")->setValue($classification->getName());
		$form->get("user_type")->setValue($classification->getUserType());
		$form->get("description")->setValue($classification->getDescription());

		$request = $this->getRequest();

		if ($request->isPost()) {

			$form->setInputFilter($classification->getInputFilter());
			$classificationData = $request->getPost()->toArray();
			$form->setData($classificationData);

			if ($form->isValid()) {

				$classification->setName($classificationData["name"]);
				$classification->setUserType($classificationData["user_type"]);
				$classification->setDescription($classificationData["description"]);


				$this->getClassificationTable()->save($classification);
				return $this->redirect()->toRoute('admin/classification');
			}
		}

		return array(
			'id' => $id,
			'form' => $form,
			'config' => $this->config
			);
	}

	public function deleteAction()
	{
		$viewModel = new ViewModel();

		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('admin/classification');
		}
		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost('del', 'No');
			if ($del == 'Si') {
				$id = (int) $request->getPost('id');
				$result = $this->getClassificationTable()->delete($id);
				if(isset($result) && $result) {
					return $this->redirect()->toRoute('admin/classification');
				}
				else {
					$viewModel->setVariable("error",true);
				}
			}
			else
				return $this->redirect()->toRoute('admin/classification');
		}
		$viewModel->setVariables(array(
			'id'=> $id,
			'config' => $this->config,
			'classification' => $this->getClassificationTable()->get($id)
			));

		return $viewModel;
	}

	public function setConfig($config)
	{
		$this->config = $config;
	}
}