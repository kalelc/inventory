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

			$nonFile = $request->getPost()->toArray();
			$file    = $this->params()->fromFiles('image');
			$data = array_merge(
					$nonFile, //POST
					array('image'=> $file['name'])
			);

			$form->setData($data);

			if ($form->isValid()) {

			if(!file_exists($this->config['component']['measure']['image_path']))
				mkdir($this->config['component']['measure']['image_path']);
				
			if(!empty($file['name'])) {
				 $size = new Size($this->config['file_characteristics']['image']['size']);
				 $extension= new Extension($this->config['file_characteristics']['image']['extension']);

				 $adapter = new HttpTransfer();
				 $adapter->setValidators(array($size,$extension), $file['name']);

				 if (!$adapter->isValid()) {
				 	dumpx("es valido");
				 	$dataError = $adapter->getMessages();

				 	$error = array();
				 	foreach($dataError as $key=>$row)
				 		$error[] = $row;

				 	$form->setMessages(array('image'=>$error));
				 }
				 else {
				 	$adapter->setDestination($this->config['component']['measure']['image_path']);

					 	foreach ($adapter->getFileInfo() as $info) {
					 		$file_name = time().'.'.pathinfo($info['name'], PATHINFO_EXTENSION) ;
					 			
					 		$adapter->addFilter('File\Rename',
					 				array('target' => $adapter->getDestination().'/'.$file_name.'',
					 						'overwrite' => true));

					 		if ($adapter->receive($info['name'])) {

					 			$measure->exchangeArray($form->getData());
					 			$measure->setImage($file_name);
					 			$this->getMeasureTable()->save($measure);
					 		}
					 	}
					}
				}
				else {
					$measure->exchangeArray($form->getData());
					$this->getMeasureTable()->save($measure);
				}
			return $this->redirect()->toRoute('measure');
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
			return $this->redirect()->toRoute('measure', array(
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
				
			$measure->getInputFilter()->get('image')->setRequired(false);
			$form->setInputFilter($measure->getInputFilter());
				
			$nonFile = $request->getPost()->toArray();
			$file    = $this->params()->fromFiles('image');
			$data = array_merge(
					$nonFile,
					array('image'=> $file['name'])
			);
				
			$form->setData($data);

			if ($form->isValid()) {
				
				$measureData = $form->getData();
				
				$size = new Size($this->config['file_characteristics']['image']['size']);
				$extension= new Extension($this->config['file_characteristics']['image']['extension']);
				$image = $form->get('image')->getValue() ;

				if(!file_exists($this->config['component']['measure']['image_path']))
					mkdir($this->config['component']['measure']['image_path']);

				$newImage = empty($data['image']) ? $previousImage : $data['image'] ;

				$measure->setSpecification($measureData["specification"]);
				$measure->setMeasureType($measureData['measure_type']);
				$measure->setMeasureValue($measureData["measure_value"]);
				$measure->setImage($newImage);
				$measure->setMeaning($measureData["meaning"]);
				$measure->setGeneralInformation($measureData["general_information"]);
				
				if(!empty($image)){
					unlink($this->config['component']['measure']['image_path']."/".$previousImage);

					$adapter = new HttpTransfer();
					$adapter->setValidators(array($size,$extension), $file['name']);

					if (!$adapter->isValid()) {
							
						$dataError = $adapter->getMessages();

						$error = array();
						foreach($dataError as $key=>$row)
							$error[] = $row;

						$form->setMessages(array('image'=>$error));
					}
					else {
							
						$adapter->setDestination($this->config['component']['measure']['image_path']);

						foreach ($adapter->getFileInfo() as $info) {
							$fileName = time().'.'.pathinfo($info['name'], PATHINFO_EXTENSION) ;
								
							$adapter->addFilter('File\Rename',
									array('target' => $adapter->getDestination().'/'.$fileName.'',
											'overwrite' => true));

							if ($adapter->receive($info['name'])) {
								$measure->setImage($fileName);
								$this->getMeasureTable()->save($measure);
							}
						}
					}
				}
				else {
					$measure->setImage($previousImage);
					$this->getMeasureTable()->save($measure);
				}

				return $this->redirect()->toRoute('measure');
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
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('measure');
		}
		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost('del', 'No');
			if ($del == 'Si') {
				$id = (int) $request->getPost('id');

				unlink($this->config['component']['measure']['image_path']."/".$this->getMeasureTable()->get($id)->getImage());
				$this->getMeasureTable()->delete($id);
			}

			return $this->redirect()->toRoute('measure');
		}
		return array(
				'id'=> $id,
				'config' => $this->config,
				'measure' => $this->getMeasureTable()->get($id)
		);
	}

	public function setConfig($config)
	{
		$this->config = $config;
	}
}