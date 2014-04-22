<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\File\Transfer\Adapter\Http  as HttpTransfer;
use Zend\Validator\File\Size;
use Zend\Validator\File\Extension;
use Admin\Model\Measure;
use Admin\Form\MeasureForm;
use Admin\Traits\ModuleTablesTrait as AdminTablesTrait;
use Application\ConfigAwareInterface;

class MeasureController extends AbstractActionController
implements ConfigAwareInterface
{
	use AdminTablesTrait;

	private $config;

	public function indexAction()
	{
		return new ViewModel(array(
			'measures' => $this->getMeasureTable()->fetchAll(),
			'config' => $this->config
			));
	}

	public function addAction()
	{
		$form = $this->getServiceLocator()->get("Admin\Form\MeasureForm");
		$request = $this->getRequest();

		if ($request->isPost()) {

			$measure = new Measure();
			$form->setInputFilter($measure->getInputFilter());

			$data = $request->getPost()->toArray();

			$form->setData($data);

			if ($form->isValid()) {

				$fileService = $this->getServiceLocator()->get('Admin\Service\FileService');

				$fileService->setDestination($this->config['component']['measure']['image_path']);
				$fileService->setSize($this->config['file_characteristics']['image']['size']);
				$fileService->setExtension($this->config['file_characteristics']['image']['extension']);

				$image = $fileService->copy($this->params()->fromFiles('image'));
				$data['image'] = $image ? $image : "" ;

				$measure->exchangeArray($data);
				$this->getMeasureTable()->save($measure);

				return $this->redirect()->toRoute('admin/measure');
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
			return $this->redirect()->toRoute('admin/measure', array(
				'action' => 'add'
				));
		}
		$measure = $this->getMeasureTable()->get($id);
		$previousImage = $measure->getImage();

		$form = $this->getServiceLocator()->get("Admin\Form\MeasureForm");
		
		$form->get("id")->setValue($measure->getId());
		$form->get("specification")->setValue($measure->getSpecification());
		$form->get("measure_type")->setValue($measure->getMeasureType());
		$form->get("measure_value")->setValue($measure->getMeasureValue());
		$form->get("meaning")->setValue($measure->getMeaning());
		$form->get("general_information")->setValue($measure->getGeneralInformation());

		$request = $this->getRequest();
		if ($request->isPost()) {

			$form->setInputFilter($measure->getInputFilter());
			$data = $request->getPost()->toArray();
			$form->setData($data);

			if ($form->isValid()) {

				$measure->setSpecification($request->getPost('specification'));
				$measure->setMeasureType($request->getPost('measure_type'));
				$measure->setMeasureValue($request->getPost('measure_value'));
				$measure->setMeaning($request->getPost('meaning'));
				$measure->setGeneralInformation($request->getPost('general_information'));

				$fileService = $this->getServiceLocator()->get('Admin\Service\FileService');
				$fileService->setDestination($this->config['component']['measure']['image_path']);
				$fileService->setSize($this->config['file_characteristics']['image']['size']);
				$fileService->setExtension($this->config['file_characteristics']['image']['extension']);

				$image = $this->params()->fromFiles('image');

				if(isset($image['name']) && !empty($image['name'])) {
					$image = $fileService->copy($image);
					$measure->setImage($image);
					if(isset($previousImage) && !empty($previousImage))
						@unlink($this->config['component']['measure']['image_path']."/".$previousImage);
				}

				$this->getMeasureTable()->save($measure);
				return $this->redirect()->toRoute('admin/measure');

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
			return $this->redirect()->toRoute('admin/measure');
		}
		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost('del', 'No');
			if ($del == 'Si') {
				$id = (int) $request->getPost('id');

				@unlink($this->config['component']['measure']['image_path']."/".$this->getMeasureTable()->get($id)->getImage());
				$result = $this->getMeasureTable()->delete($id);

				if(isset($result) && $result) {
					return $this->redirect()->toRoute('admin/bank');
				}
				else {
					$viewModel->setVariable("error",true);
				}
			}
			else
				return $this->redirect()->toRoute('admin/measure');
		}
		$viewModel->setVariables(array(
			'id'=> $id,
			'config' => $this->config,
			'measure' => $this->getMeasureTable()->get($id)
			));
		return $viewModel;
	}

	public function setConfig($config)
	{
		$this->config = $config;
	}
}