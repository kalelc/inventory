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

			$nonFile = $request->getPost()->toArray();
			$file    = $this->params()->fromFiles('image');
			$data = array_merge(
					$nonFile,
					array('image'=> $file['name'])
			);

			$form->setData($data);

			if ($form->isValid()) {

				if(!file_exists($this->config['component']['specification']['image_path']))
					mkdir($this->config['component']['specification']['image_path']);
				
				if(!empty($file['name'])) {
				 $size = new Size($this->config['file_characteristics']['image']['size']);
				 $extension= new Extension($this->config['file_characteristics']['image']['extension']);

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
				 	$adapter->setDestination($this->config['component']['specification']['image_path']);

					 	foreach ($adapter->getFileInfo() as $info) {
					 		$fileName = time().'.'.pathinfo($info['name'], PATHINFO_EXTENSION) ;
					 			
					 		$adapter->addFilter('File\Rename',
					 				array('target' => $adapter->getDestination().'/'.$fileName.'',
					 						'overwrite' => true));

					 		if ($adapter->receive($info['name'])) {

					 			$specification->exchangeArray($form->getData());
					 			$specification->setImage($fileName);
					 			$this->getSpecificationTable()->save($specification);

					 			
					 		}
					 	}
					}
				}
				else {
					$specification->exchangeArray($form->getData());
		 			$this->getSpecificationTable()->save($specification);
				}
			return $this->redirect()->toRoute('specification');
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
			return $this->redirect()->toRoute('specification', array(
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
				
			$specification->getInputFilter()->get('image')->setRequired(false);
			$form->setInputFilter($specification->getInputFilter());
				
			$nonFile = $request->getPost()->toArray();
			$file    = $this->params()->fromFiles('image');
			$data = array_merge(
					$nonFile,
					array('image'=> $file['name'])
			);
				
			$form->setData($data);
			if ($form->isValid()) {
				
				$specificationData = $form->getData();
				
				$size = new Size($this->config['file_characteristics']['image']['size']);
				$extension= new Extension($this->config['file_characteristics']['image']['extension']);
				$image = $form->get('image')->getValue() ;

				if(!file_exists($this->config['component']['specification']['image_path']))
					mkdir($this->config['component']['specification']['image_path']);
								
				$newImage = empty($data['image']) ? $previousImage : $data['image'] ;
				
				$specification->setName($specificationData["name"]);
				$specification->setImage($newImage);
				$specification->setSpecificationMaster($specificationData["specification_master"]);
				$specification->setMeaning($specificationData["meaning"]);
				$specification->setGeneralInformation($specificationData["general_information"]);
				
				
				if(!empty($image)){
					unlink($this->config['specification']['image_path']."/".$previousImage);

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
							
						$adapter->setDestination($this->config['component']['specification']['image_path']);

						foreach ($adapter->getFileInfo() as $info) {
							$fileName = time().'.'.pathinfo($info['name'], PATHINFO_EXTENSION) ;
								
							$adapter->addFilter('File\Rename',
									array('target' => $adapter->getDestination().'/'.$fileName.'',
											'overwrite' => true));

							if ($adapter->receive($info['name'])) {
								$specification->setImage($fileName);
								$this->getSpecificationTable()->save($specification);
							}
						}
					}
				}
				else {
					$specification->setImage($previousImage);
					$this->getSpecificationTable()->save($specification);
				}

				return $this->redirect()->toRoute('specification');
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
			return $this->redirect()->toRoute('specification');
		}
		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost('del', 'No');
			if ($del == 'Si') {
				$id = (int) $request->getPost('id');
				unlink($this->config['component']['specification']['image_path']."/".$this->getSpecificationTable()->get($id)->getImage());
				$this->getSpecificationTable()->delete($id);
			}

			return $this->redirect()->toRoute('specification');
		}
		return array(
				'id'=> $id,
				'config' => $this->config,
				'specification' => $this->getSpecificationTable()->get($id)
		);
	}

	public function setConfig($config)
	{
		$this->config = $config;
	}
}