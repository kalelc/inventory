<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\File\Transfer\Adapter\Http  as HttpTransfer;
use Zend\Validator\File\Size;
use Zend\Validator\File\Extension;
use Admin\Model\Brand;
use Admin\Form\BrandForm;
use Admin\Traits\ModuleTablesTrait as AdminTablesTrait;
use Application\ConfigAwareInterface;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Iterator as PaginatorIterator;

class BrandController extends AbstractActionController
implements ConfigAwareInterface
{
	use AdminTablesTrait;
	private $config;

	public function indexAction()
	{
		$page = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : 1;

		$brands = $this->getBrandTable()->fetchAll();
		$paginator = new Paginator(new PaginatorIterator($brands));
		$paginator->setCurrentPageNumber($page)
		->setItemCountPerPage($this->config['pagination']['itempage'])
		->setPageRange($this->config['pagination']['pagerange']);

		return new ViewModel(array(
			'brands' => $paginator,
			'config' => $this->config
			));
	}

	public function addAction()
	{
		$form = new BrandForm();
		$request = $this->getRequest();

		if ($request->isPost()) {

			$brand = new Brand();

			$form->setInputFilter($brand->getInputFilter());
			$data = $request->getPost()->toArray();
			$form->setData($data);

			if ($form->isValid()) {

				$fileService = $this->getServiceLocator()->get('Admin\Service\FileService');

				$fileService->setDestination($this->config['component']['brand']['image_path']);
				$fileService->setSize($this->config['file_characteristics']['image']['size']);
				$fileService->setExtension($this->config['file_characteristics']['image']['extension']);

				$image = $fileService->copy($this->params()->fromFiles('image'));
				$backgroundImage = $fileService->copy($this->params()->fromFiles('background_image'));

				$data['image'] = $image ? $image : "" ;
				$data['background_image'] = $backgroundImage ? $backgroundImage : ""  ;

				$brand->exchangeArray($data);
				$this->getBrandTable()->save($brand);

				return $this->redirect()->toRoute('admin/brand');
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

		if (!$id)
			return $this->redirect()->toRoute('admin/brand', array('action' => 'add'));

		$brand = $this->getBrandTable()->get($id);
		$previousImage = $brand->getImage();
		$previousBackgroundImage = $brand->getBackgroundImage();

		$form  = new BrandForm();

		$form->get("name")->setValue($brand->getName());
		$form->get("description")->setValue($brand->getDescription());

		$request = $this->getRequest();
		if ($request->isPost()) {

			$form->setInputFilter($brand->getInputFilter());
			$data = $request->getPost()->toArray();
			$form->setData($data);

			if ($form->isValid()) {

				$brand->setName($request->getPost('name'));
				$brand->setDescription($request->getPost('description'));

				$fileService = $this->getServiceLocator()->get('Admin\Service\FileService');
				$fileService->setDestination($this->config['component']['brand']['image_path']);
				$fileService->setSize($this->config['file_characteristics']['image']['size']);
				$fileService->setExtension($this->config['file_characteristics']['image']['extension']);

				$image = $this->params()->fromFiles('image');
				$backgroundImage = $this->params()->fromFiles('background_image');

				if(isset($image['name']) && !empty($image['name'])) {
					$image = $fileService->copy($image);
					$brand->setImage($image);
					if(isset($previousImage) && !empty($previousImage))
						unlink($this->config['component']['brand']['image_path']."/".$previousImage);
				}
				if(isset($backgroundImage['name']) && !empty($backgroundImage['name'])) {
					$backgroundImage = $fileService->copy($backgroundImage);
					$brand->setBackgroundImage($backgroundImage);
					if(isset($previousBackgroundImage) && !empty($previousBackgroundImage))
						unlink($this->config['component']['brand']['image_path']."/".$previousBackgroundImage);
				}

				$this->getBrandTable()->save($brand);
				return $this->redirect()->toRoute('admin/brand');
			}
		}

		return array(
			'id' => $id,
			'image' => $previousImage,
			'backgroundImage' => $previousBackgroundImage,
			'form' => $form,
			'config' => $this->config,
			);
	}

	public function deleteAction()
	{
		$viewModel = new ViewModel();

		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('admin/brand');
		}
		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost('del', 'No');
			if ($del == 'Si') {
				$id = (int) $request->getPost('id');

				$brandTable = $this->getBrandTable()->get($id);

				unlink($this->config['component']['brand']['image_path']."/".$brandTable->getImage());
				unlink($this->config['component']['brand']['image_path']."/".$brandTable->getBackgroundImage());

				$result = $this->getBrandTable()->delete($id);

				if(isset($result) && $result) {
					return $this->redirect()->toRoute('admin/brand');
				}
				else {
					$viewModel->setVariable("error",true);
				}
			}
			else
				return $this->redirect()->toRoute('admin/brand');
		}
		$viewModel->setVariables(array(
			'id'=> $id,
			'config' => $this->config,
			'brand' => $this->getBrandTable()->get($id)
			));
	}

	public function setConfig($config){
		$this->config = $config;
	}
}