<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\File\Transfer\Adapter\Http  as HttpTransfer;
use Zend\Validator\File\Size;
use Zend\Validator\File\Extension;
use Admin\Model\SpecificationMaster;
use Admin\Form\SpecificationMasterForm;
use Admin\Traits\ModuleTablesTrait as AdminTablesTrait;
use Application\ConfigAwareInterface;

class SpecificationMasterController extends AbstractActionController
implements ConfigAwareInterface
{
	use AdminTablesTrait;
	private $config;

	public function indexAction()
	{
		return new ViewModel(array(
			'specificationsMasters' => $this->getSpecificationMasterTable()->fetchAll(),
			'config' => $this->config
			));
	}

	public function addAction()
	{
		$form = new SpecificationMasterForm();
		$request = $this->getRequest();

		if ($request->isPost()) {

			$specificationMaster = new SpecificationMaster();

			$form->setInputFilter($specificationMaster->getInputFilter());
			$data = $request->getPost()->toArray();
			$form->setData($data);

			if ($form->isValid()) {

				$fileService = $this->getServiceLocator()->get('Admin\Service\FileService');

				$fileService->setDestination($this->config['component']['specification_master']['image_path']);
				$fileService->setSize($this->config['file_characteristics']['image']['size']);
				$fileService->setExtension($this->config['file_characteristics']['image']['extension']);

				$image = $fileService->copy($this->params()->fromFiles('image'));
				$backgroundImage = $fileService->copy($this->params()->fromFiles('background_image'));

				$data['image'] = $image ? $image : "" ;
				$data['background_image'] = $backgroundImage ? $backgroundImage : ""  ;

				$specificationMaster->exchangeArray($data);
				$this->getBrandTable()->save($specificationMaster);

				return $this->redirect()->toRoute('admin/specification_master');
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
			return $this->redirect()->toRoute('admin/specification_master', array(
				'action' => 'add'
				));
		}
		$specificationMaster = $this->getSpecificationMasterTable()->get($id);
		$previousImage = $specificationMaster->getImage();

		$form  = new SpecificationMasterForm();

		$form->get("name")->setValue($specificationMaster->getName());
		$form->get("description")->setValue($specificationMaster->getDescription());

		$request = $this->getRequest();
		if ($request->isPost()) {

			$form->setInputFilter($specificationMaster->getInputFilter());
			$data = $request->getPost()->toArray();
			$form->setData($data);

			if ($form->isValid()) {

				$specificationMaster->setName($request->getPost('name'));
				$specificationMaster->setDescription($request->getPost('description'));

				$fileService = $this->getServiceLocator()->get('Admin\Service\FileService');
				$fileService->setDestination($this->config['component']['specification_master']['image_path']);
				$fileService->setSize($this->config['file_characteristics']['image']['size']);
				$fileService->setExtension($this->config['file_characteristics']['image']['extension']);

				$image = $this->params()->fromFiles('image');
				$backgroundImage = $this->params()->fromFiles('background_image');

				if(isset($image['name']) && !empty($image['name'])) {
					$image = $fileService->copy($image);
					$specificationMaster->setImage($image);
					if(isset($previousImage) && !empty($previousImage))
						unlink($this->config['component']['specification_master']['image_path']."/".$previousImage);
				}

				$this->getBrandTable()->save($specificationMaster);
				return $this->redirect()->toRoute('admin/specification_master');
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
			return $this->redirect()->toRoute('admin/specification_master');
		}
		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost('del', 'No');
			if ($del == 'Si') {
				$id = (int) $request->getPost('id');
				unlink($this->config['component']['specification_master']['image_path']."/".$this->getSpecificationMasterTable()->get($id)->getImage());
				$this->getSpecificationMasterTable()->delete($id);
			}

			return $this->redirect()->toRoute('admin/specification_master');
		}
		return array(
			'id'=> $id,
			'config' => $this->config,
			'specificationMaster' => $this->getSpecificationMasterTable()->get($id)
			);
	}

	public function setConfig($config){
		$this->config = $config;
	}
}