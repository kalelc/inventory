<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\File\Transfer\Adapter\Http  as HttpTransfer;
use Zend\Validator\File\Size;
use Zend\Validator\File\Extension;
use Admin\Model\Specification;
use Admin\Form\SpecificationForm;
use Admin\Traits\ModuleTablesTrait as AdminTablesTrait;
use Application\ConfigAwareInterface;

class SpecificationController extends AbstractActionController
implements ConfigAwareInterface
{
	use AdminTablesTrait;
	private $config;
	
	public function indexAction()
	{
		return new ViewModel(array(
			'specifications' => $this->getSpecificationTable()->fetchAll(),
			'config' => $this->config
			));
	}

	public function addAction()
	{
		$form = $this->getServiceLocator()->get("Admin\Form\SpecificationForm");
		$request = $this->getRequest();

		if ($request->isPost()) {

			$specification = new Specification();
			$form->setInputFilter($specification->getInputFilter());
			$data = $request->getPost()->toArray();
			$form->setData($data);

			if ($form->isValid()) {

				$fileService = $this->getServiceLocator()->get('Admin\Service\FileService');

				$fileService->setDestination($this->config['component']['specification']['image_path']);
				$fileService->setSize($this->config['file_characteristics']['image']['size']);
				$fileService->setExtension($this->config['file_characteristics']['image']['extension']);

				$image = $fileService->copy($this->params()->fromFiles('image'));
				$backgroundImage = $fileService->copy($this->params()->fromFiles('background_image'));

				$data['image'] = $image ? $image : "" ;

				$specification->exchangeArray($data);
				$this->getSpecificationTable()->save($specification);

				return $this->redirect()->toRoute('admin/specification');
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
			return $this->redirect()->toRoute('admin/specification', array(
				'action' => 'add'
				));
		}
		$specification = $this->getSpecificationTable()->get($id);
		$previousImage = $specification->getImage();

		$form = $this->getServiceLocator()->get("Admin\Form\SpecificationForm");
		
		$form->get("name")->setValue($specification->getName());
		$form->get("specification_master")->setValue($specification->getSpecificationMaster());
		$form->get("meaning")->setValue($specification->getMeaning());
		$form->get("general_information")->setValue($specification->getGeneralInformation());

		$request = $this->getRequest();

		if ($request->isPost()) {

			$form->setInputFilter($specification->getInputFilter());
			$data = $request->getPost()->toArray();
			$form->setData($data);

			if ($form->isValid()) {

				$specification->setName($specificationData["name"]);
				$specification->setImage($newImage);
				$specification->setSpecificationMaster($specificationData["specification_master"]);
				$specification->setMeaning($specificationData["meaning"]);
				$specification->setGeneralInformation($specificationData["general_information"]);
				$specification->setName($request->getPost('name'));
				$specification->setDescription($request->getPost('description'));

				$fileService = $this->getServiceLocator()->get('Admin\Service\FileService');
				$fileService->setDestination($this->config['component']['specification']['image_path']);
				$fileService->setSize($this->config['file_characteristics']['image']['size']);
				$fileService->setExtension($this->config['file_characteristics']['image']['extension']);

				$image = $this->params()->fromFiles('image');

				if(isset($image['name']) && !empty($image['name'])) {
					$image = $fileService->copy($image);
					$specification->setImage($image);
					if(isset($previousImage) && !empty($previousImage))
						@unlink($this->config['component']['specification']['image_path']."/".$previousImage);
				}

				$this->getSpecificationTable()->save($specification);
				return $this->redirect()->toRoute('admin/specification');
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
			return $this->redirect()->toRoute('admin/specification');
		}
		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost('del', 'No');
			if ($del == 'Si') {
				$id = (int) $request->getPost('id');
				@unlink($this->config['component']['specification']['image_path']."/".$this->getSpecificationTable()->get($id)->getImage());
				
				$result = $this->getSpecificationTable()->delete($id);
				if(isset($result) && $result) {
					return $this->redirect()->toRoute('admin/specification');
				}
				else {
					$viewModel->setVariable("error",true);
				}
			}
			else
				return $this->redirect()->toRoute('admin/specification');
		}
		$viewModel->setVariables(array(
			'id'=> $id,
			'config' => $this->config,
			'specification' => $this->getSpecificationTable()->get($id)
			));

		return $viewModel;
	}

	public function setConfig($config)
	{
		$this->config = $config;
	}
}