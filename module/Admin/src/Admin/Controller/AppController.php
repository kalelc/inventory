<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Model\App;
use Zend\File\Transfer\Adapter\Http  as HttpTransfer;
use Zend\Validator\File\Size;
use Zend\Validator\File\Extension;
use Admin\Form\AppForm;
use Admin\Traits\ModuleTablesTrait as AdminTablesTrait;
use Application\ConfigAwareInterface;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Iterator as PaginatorIterator;

class AppController extends AbstractActionController
implements ConfigAwareInterface
{
	use AdminTablesTrait;
	private $config;

	public function indexAction()
	{

		$page = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : 1;

		$apps = $this->getAppTable()->fetchAll();
		$paginator = new Paginator(new PaginatorIterator($apps));
		$paginator->setCurrentPageNumber($page)
		->setItemCountPerPage($this->config['pagination']['itempage'])
		->setPageRange($this->config['pagination']['pagerange']);

		return new ViewModel(array(
			'apps' => $paginator,
			'config' => $this->config
			));
	}

	public function addAction()
	{
		$form = new AppForm();
		$request = $this->getRequest();

		if ($request->isPost()) {

			$app = new App();

			$form->setInputFilter($app->getInputFilter());
			$data = $request->getPost()->toArray();
			$form->setData($data);

			if ($form->isValid()) {

				$fileService = $this->getServiceLocator()->get('Admin\Service\FileService');

				$fileService->setDestination($this->config['component']['app']['image_path']);
				$fileService->setSize($this->config['file_characteristics']['image']['size']);
				$fileService->setExtension($this->config['file_characteristics']['image']['extension']);

				$image = $fileService->copy($this->params()->fromFiles('image'));

				$data['image'] = $image ? $image : "" ;

				$app->exchangeArray($data);
				$this->getAppTable()->save($app);

				return $this->redirect()->toRoute('admin/app');
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
			return $this->redirect()->toRoute('admin/app', array('action' => 'add'));
		}
		$app = $this->getAppTable()->get($id);
		$previousImage = $app->getImage();

		$form  = new appForm();

		$form->get("name")->setValue($app->getName());
		$form->get("description")->setValue($app->getDescription());

		$request = $this->getRequest();
		if ($request->isPost()) {

			$form->setInputFilter($app->getInputFilter());
			$data = $request->getPost()->toArray();
			$form->setData($data);

			if ($form->isValid()) {

				$app->setName($request->getPost('name'));
				$app->setDescription($request->getPost('description'));

				$fileService = $this->getServiceLocator()->get('Admin\Service\FileService');
				$fileService->setDestination($this->config['component']['app']['image_path']);
				$fileService->setSize($this->config['file_characteristics']['image']['size']);
				$fileService->setExtension($this->config['file_characteristics']['image']['extension']);

				$image = $this->params()->fromFiles('image');
				$backgroundImage = $this->params()->fromFiles('background_image');

				if(isset($image['name']) && !empty($image['name'])) {
					$image = $fileService->copy($image);
					$app->setImage($image);
					if(isset($previousImage) && !empty($previousImage))
						@unlink($this->config['component']['app']['image_path']."/".$previousImage);
				}

				$this->getAppTable()->save($app);
				return $this->redirect()->toRoute('admin/app');
			}
		}

		return array(
			'id' => $id,
			'image' => $previousImage,
			'form' => $form,
			'config' => $this->config,
			);
	}

	public function deleteAction()
	{
		$viewModel = new ViewModel();
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('admin/app');
		}
		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost('del', 'No');
			if ($del == 'Si') {
				$id = (int) $request->getPost('id');
				@unlink($this->config['component']['app']['image_path']."/".$this->getAppTable()->get($id)->getImage());
				$result = $this->getAppTable()->delete($id);

				if(isset($result) && $result) {
					return $this->redirect()->toRoute('admin/app');
				}
				else {
					$viewModel->setVariable("error",true);
				}
			}
			else
				return $this->redirect()->toRoute('admin/app');
		}
		$viewModel->setVariables(array(
			'id'=> $id,
			'config' => $this->config,
			'app' => $this->getAppTable()->get($id)
			));

		return $viewModel;
	}

	public function setConfig($config){
		$this->config = $config;
	}
}