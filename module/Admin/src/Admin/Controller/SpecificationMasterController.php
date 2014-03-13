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

			$nonFile = $request->getPost()->toArray();
			$file    = $this->params()->fromFiles('image');
			$data = array_merge(
					$nonFile,
					array('image'=> $file['name'])
			);

			$form->setData($data);

			if ($form->isValid()) {
				if(!file_exists($this->config['component']['specification_master']['image_path']))
					mkdir($this->config['component']['specification_master']['image_path']);

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
				 		$adapter->setDestination($this->config['component']['specification_master']['image_path']);
					 	foreach ($adapter->getFileInfo() as $info) {
					 		$fileName = time().'.'.pathinfo($info['name'], PATHINFO_EXTENSION) ;
					 		
					 		$adapter->addFilter('File\Rename',
					 				array('target' => $adapter->getDestination().'/'.$fileName.'',
					 						'overwrite' => true));
					 		 
					 		if ($adapter->receive($info['name'])) {
					 			
					 			$specificationMaster->exchangeArray($form->getData());
					 			$specificationMaster->setImage($fileName);
					 			$this->getSpecificationMasterTable()->save($specificationMaster);

					 			return $this->redirect()->toRoute('admin/specification_master');
					 		}
					 	}
					}

				}
				else {
					$specificationMasterData = $form->getData();
				
					$specificationMaster->setName($specificationMasterData["name"]);
					$specificationMaster->setDescription($specificationMasterData["description"]);
					$this->getSpecificationMasterTable()->save($specificationMaster);

					return $this->redirect()->toRoute('admin/specification_master');
				}
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
			
			$specificationMaster->getInputFilter()->get('image')->setRequired(false);
			$form->setInputFilter($specificationMaster->getInputFilter());
				
			
			$nonFile = $request->getPost()->toArray();
			$file    = $this->params()->fromFiles('image');
			$data = array_merge(
					$nonFile, 
					array('image'=> $file['name'])
			);
			
			$form->setData($data);
			
			if ($form->isValid()) {
				
				$specificationMasterData = $form->getData();
				
				$size = new Size($this->config['file_characteristics']['image']['size']);
				$extension= new Extension($this->config['file_characteristics']['image']['extension']);
				$image = $form->get('image')->getValue() ;
				
				if(!file_exists($this->config['component']['specification_master']['image_path'])){
					mkdir($this->config['component']['specification_master']['image_path']);
				}
				
				$newImage = empty($data['image']) ? $previousImage : $data['image'] ;
				
				$specificationMaster->setName($specificationMasterData["name"]);
				$specificationMaster->setImage($newImage);
				$specificationMaster->setDescription($specificationMasterData["description"]);
				
				
				if(!empty($image)){
 					unlink($this->config['component']['specification_master']['image_path']."/".$previousImage);
  				
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
 						
 						$adapter->setDestination($this->config['component']['specification_master']['image_path']);
 					
 						foreach ($adapter->getFileInfo() as $info) {
 							$fileName = time().'.'.pathinfo($info['name'], PATHINFO_EXTENSION) ;
 								
 							$adapter->addFilter('File\Rename',
 									array('target' => $adapter->getDestination().'/'.$fileName.'',
 											'overwrite' => true));
 					
 							if ($adapter->receive($info['name'])) {
 								$specificationMaster->setImage($fileName);
 								$this->getSpecificationMasterTable()->save($specificationMaster);
 							}
 						}
 					}
 				}
 				else {
 					$specificationMaster->setImage($previousImage);
 					$this->getSpecificationMasterTable()->save($specificationMaster);
 				}
				
 				return $this->redirect()->toRoute('admin/specification_master');
			}
		}

		return array(
				'id' => $id,
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