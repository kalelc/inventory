<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Model\MasterCategory;
use Zend\File\Transfer\Adapter\Http  as HttpTransfer;
use Zend\Validator\File\Size;
use Zend\Validator\File\Extension;
use Admin\Form\MasterCategoryForm;
use Admin\Traits\ModuleTablesTrait as AdminTablesTrait;
use Application\ConfigAwareInterface;

class MasterCategoryController extends AbstractActionController
implements ConfigAwareInterface
{
	use AdminTablesTrait;
	private $config;

	public function indexAction()
	{
		return new ViewModel(array(
			'masterCategories' => $this->getMasterCategoryTable()->fetchAll(),
			'config' => $this->config
			));
	}

	public function addAction()
	{
		$form = new MasterCategoryForm();
		$request = $this->getRequest();

		if ($request->isPost()) {

			$masterCategory = new MasterCategory();

			$form->setInputFilter($masterCategory->getInputFilter());
			$data = $request->getPost()->toArray();
			$form->setData($data);

			if ($form->isValid()) {

				$fileService = $this->getServiceLocator()->get('Admin\Service\FileService');

				$fileService->setDestination($this->config['component']['master_category']['image_path']);
				$fileService->setSize($this->config['file_characteristics']['image']['size']);
				$fileService->setExtension($this->config['file_characteristics']['image']['extension']);

				$image = $fileService->copy($this->params()->fromFiles('image'));

				$data['image'] = $image ? $image : "" ;

				$masterCategory->exchangeArray($data);
				$this->getMasterCategoryTable()->save($masterCategory);

				return $this->redirect()->toRoute('admin/master_category');
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
			return $this->redirect()->toRoute('admin/master_category', array('action' => 'add'));
		}
		$masterCategory = $this->getMasterCategoryTable()->get($id);
		$previousImage = $masterCategory->getImage();

		$form  = new masterCategoryForm();

		$form->get("name")->setValue($masterCategory->getName());
		$form->get("description")->setValue($masterCategory->getDescription());

		$request = $this->getRequest();
		if ($request->isPost()) {

			$form->setInputFilter($masterCategory->getInputFilter());
			$data = $request->getPost()->toArray();
			$form->setData($data);

			if ($form->isValid()) {

				$masterCategory->setName($request->getPost('name'));
				$masterCategory->setDescription($request->getPost('description'));

				$fileService = $this->getServiceLocator()->get('Admin\Service\FileService');
				$fileService->setDestination($this->config['component']['master_category']['image_path']);
				$fileService->setSize($this->config['file_characteristics']['image']['size']);
				$fileService->setExtension($this->config['file_characteristics']['image']['extension']);

				$image = $this->params()->fromFiles('image');
				$backgroundImage = $this->params()->fromFiles('background_image');

				if(isset($image['name']) && !empty($image['name'])) {
					$image = $fileService->copy($image);
					$masterCategory->setImage($image);
					if(isset($previousImage) && !empty($previousImage))
						unlink($this->config['component']['master_category']['image_path']."/".$previousImage);
				}

				$this->getMasterCategoryTable()->save($masterCategory);
				return $this->redirect()->toRoute('admin/masterCategory');
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
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('admin/master_category');
		}
		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost('del', 'No');
			if ($del == 'Si') {
				$id = (int) $request->getPost('id');
				unlink($this->config['component']['master_category']['image_path']."/".$this->getMasterCategoryTable()->get($id)->getImage());
				$this->getMasterCategoryTable()->delete($id);
			}

			return $this->redirect()->toRoute('admin/master_category');
		}
		return array(
			'id'=> $id,
			'config' => $this->config,
			'masterCategory' => $this->getMasterCategoryTable()->get($id)
			);
	}

	public function setConfig($config){
		$this->config = $config;
	}
}