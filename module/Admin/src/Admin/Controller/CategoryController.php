<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\File\Transfer\Adapter\Http  as HttpTransfer;
use Zend\Validator\File\Size;
use Zend\Validator\File\Extension;
use Admin\Model\Category;
use Admin\Form\CategoryForm;
use Admin\Traits\ModuleTablesTrait as AdminTablesTrait;
use Application\ConfigAwareInterface;
use Admin\Model\CategorySpecification;

class CategoryController extends AbstractActionController
implements ConfigAwareInterface
{
	use AdminTablesTrait;
	private $config;
	
	public function indexAction()
	{
		return new ViewModel(array(
			'categories' => $this->getCategoryTable()->fetchAll(),
			'config' => $this->config
			));
	}

	public function addAction()
	{
		$form = $this->getServiceLocator()->get("Admin\Form\CategoryForm");

		
		if(count($form->getMasterCategoryList())==0)
			return $this->redirect()->toRoute('admin/master_category');
		if(count($form->getSerialNameList())==0)
			return $this->redirect()->toRoute('admin/serial_name');
		if(count($form->getSpecificationList())==0)
			return $this->redirect()->toRoute('admin/specification');

		$request = $this->getRequest();

		if ($request->isPost()) {

			$category = new Category();

			$form->setInputFilter($category->getInputFilter());
			$data = $request->getPost()->toArray();
			$form->setData($data);

			if ($form->isValid()) {

				$fileService = $this->getServiceLocator()->get('Admin\Service\FileService');

				$fileService->setDestination($this->config['component']['category']['image_path']);
				$fileService->setSize($this->config['file_characteristics']['image']['size']);
				$fileService->setExtension($this->config['file_characteristics']['image']['extension']);

				$image = $fileService->copy($this->params()->fromFiles('image'));

				$data['image'] = $image ? $image : "" ;

				$category->exchangeArray($data);
				$categoryId = $this->getCategoryTable()->save($category);

				$serialName = $data['serial_name'];
				$specification = $data['specification'];

				$this->getCategorySerialNameTable()->save($categoryId,$serialName);
				$this->getCategorySpecificationTable()->save($categoryId,$specification);

				return $this->redirect()->toRoute('admin/category');
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
			return $this->redirect()->toRoute('admin/category', array(
				'action' => 'add'
				));
		}

		$specificationsCheck = $this->getCategorySpecificationTable()->getCategorySpecificationCheckValue($id);
		$specificationsUncheck = $this->getCategorySpecificationTable()->getCategorySpecificationUncheckValue();

		$specificationValueOptions = array();
		
		foreach($specificationsCheck as $specificationCheck) {
			$specificationValueOptions[$specificationCheck->getSpecification()] = $specificationCheck->getSpecificationName();
		}

		foreach($specificationsUncheck as $specificationUncheck) {
			$specificationValueOptions[$specificationUncheck->getSpecification()] = $specificationUncheck->getSpecificationName();
		}

		$category = $this->getCategoryTable()->get($id);
		$previousImage = $category->getImage();

		$form = $this->getServiceLocator()->get("Admin\Form\CategoryForm");
		
		$form->get("master_category")->setValue($category->getMasterCategory());
		$form->get("singular_name")->setValue($category->getSingularName());
		$form->get("plural_name")->setValue($category->getPluralName());
		$form->get("shipping_cost")->setValue($category->getShippingCost());
		$form->get("additional_shipping")->setValue($category->getAdditionalShipping());
		$form->get("description")->setValue($category->getDescription());

		$serialNameValues = $this->getCategorySerialNameTable()->get($category->getId());
		$specificationValues = $this->getCategorySpecificationTable()->get($category->getId());

		$form->get("serial_name")->setValue($serialNameValues);
		
		$form->get('specification')->setValueOptions($specificationValueOptions);
		$form->get("specification")->setValue($specificationValues);

		$request = $this->getRequest();
		if ($request->isPost()) {

			$category->getInputFilter()->get('image')->setRequired(false);
			$form->setInputFilter($category->getInputFilter());

			$nonFile = $request->getPost()->toArray();
			$file    = $this->params()->fromFiles('image');
			$categoryData = array_merge($nonFile,array('image'=> $file['name']));

			$form->setData($categoryData);

			if ($form->isValid()) {

				$category->setMasterCategory($categoryData["master_category"]);
				$category->setSingularName($categoryData["singular_name"]);
				$category->setPluralName($categoryData["plural_name"]);
				$category->setShippingCost($categoryData["shipping_cost"]);
				$category->setAdditionalShipping($categoryData["additional_shipping"]);
				$category->setDescription($categoryData["description"]);

				$fileService = $this->getServiceLocator()->get('Admin\Service\FileService');
				$fileService->setDestination($this->config['component']['category']['image_path']);
				$fileService->setSize($this->config['file_characteristics']['image']['size']);
				$fileService->setExtension($this->config['file_characteristics']['image']['extension']);

				$image = $this->params()->fromFiles('image');

				if(isset($image['name']) && !empty($image['name'])) {
					$image = $fileService->copy($image);
					$category->setImage($image);
					if(isset($previousImage) && !empty($previousImage))
						@unlink($this->config['component']['category']['image_path']."/".$previousImage);
				}

				$categoryId = $this->getCategoryTable()->save($category);

				$serialName = $categoryData['serial_name'];
				$specification = $categoryData['specification'];

				$this->getCategorySerialNameTable()->save($categoryId,$serialName);
				$this->getCategorySpecificationTable()->save($categoryId,$specification);

				return $this->redirect()->toRoute('admin/category');
			}
		}

		return array(
			'id' => $id,
			'image' => $previousImage,
			'form' => $form,
			'config' => $this->config
			);
	}

	public function deleteAction()
	{
		$viewModel = new ViewModel();

		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('admin/category');
		}
		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost('del', 'No');
			if ($del == 'Si') {
				$id = (int) $request->getPost('id');
				@unlink($this->config['component']['category']['image_path']."/".$this->getCategoryTable()->get($id)->getImage());

				$this->getCategorySerialNameTable()->delete($id);
				$this->getCategorySpecificationTable()->delete($id);
				$result = $this->getCategoryTable()->delete($id);

				if(isset($result) && $result) {
					return $this->redirect()->toRoute('admin/category');
				}
				else {
					$viewModel->setVariable("error",true);
				}
			}
			else
				return $this->redirect()->toRoute('admin/category');
		}
		$viewModel->setVariables(array(
			'id'=> $id,
			'config' => $this->config,
			'category' => $this->getCategoryTable()->get($id)
			));

		return $viewModel;
	}

	public function setConfig($config)
	{
		$this->config = $config;
	}
}