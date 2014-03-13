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

			$nonFile = $request->getPost()->toArray();
			$file    = $this->params()->fromFiles('image');
			$data = array_merge(
					$nonFile, //POST
					array('image'=> $file['name'])
			);

			$form->setData($data);

			if ($form->isValid()) {
				if(!file_exists($this->config['component']['master_category']['image_path'])){
					mkdir($this->config['component']['master_category']['image_path']);
				}

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
				 		$adapter->setDestination($this->config['component']['master_category']['image_path']);

					 	foreach ($adapter->getFileInfo() as $info) {
					 		$fileName = time().'.'.pathinfo($info['name'], PATHINFO_EXTENSION) ;
					 		
					 		$adapter->addFilter('File\Rename',
					 				array('target' => $adapter->getDestination().'/'.$fileName.'',
					 						'overwrite' => true));
					 		 
					 		if ($adapter->receive($info['name'])) {
					 			
					 			$masterCategory->exchangeArray($form->getData());
					 			$masterCategory->setImage($fileName);
					 			$this->getMasterCategoryTable()->save($masterCategory);

					 			return $this->redirect()->toRoute('admin/master_category');
					 		}
					 	}
					}

				}
				else {
					$masterCategory->exchangeArray($form->getData());
					$this->getMasterCategoryTable()->save($masterCategory);

					return $this->redirect()->toRoute('admin/master_category');
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
			return $this->redirect()->toRoute('admin/master_category', array(
					'action' => 'add'
			));
		}
		$masterCategory = $this->getMasterCategoryTable()->get($id);
		$previousImage = $masterCategory->getImage();
			
		$form  = new masterCategoryForm();
		
		$form->get("name")->setValue($masterCategory->getName());
		$form->get("description")->setValue($masterCategory->getDescription());
			
		$request = $this->getRequest();
		if ($request->isPost()) {
			
			$masterCategory->getInputFilter()->get('image')->setRequired(false);
			$form->setInputFilter($masterCategory->getInputFilter());
				
			
			$nonFile = $request->getPost()->toArray();
			$file    = $this->params()->fromFiles('image');
			$data = array_merge(
					$nonFile, 
					array('image'=> $file['name'])
			);
			
			$form->setData($data);
			
			if ($form->isValid()) {
				
				$masterCategoryData = $form->getData();
				
				$size = new Size($this->config['file_characteristics']['image']['size']);
				$extension= new Extension($this->config['file_characteristics']['image']['extension']);
				$image = $form->get('image')->getValue() ;
				
				if(!file_exists($this->config['component']['master_category']['image_path'])){
					mkdir($this->config['component']['master_category']['image_path']);
				}
				
				$newImage = empty($data['image']) ? $previousImage : $data['image'] ;
				
				$masterCategory->setName($masterCategoryData["name"]);
				$masterCategory->setImage($newImage);
				$masterCategory->setDescription($masterCategoryData["description"]);
				
				
				if(!empty($image)){
 					unlink($this->config['component']['master_category']['image_path']."/".$previousImage);
  				
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
 						
 						$adapter->setDestination($this->config['component']['master_category']['image_path']);
 					
 						foreach ($adapter->getFileInfo() as $info) {
 							$fileName = time().'.'.pathinfo($info['name'], PATHINFO_EXTENSION) ;
 								
 							$adapter->addFilter('File\Rename',
 									array('target' => $adapter->getDestination().'/'.$fileName.'',
 											'overwrite' => true));
 					
 							if ($adapter->receive($info['name'])) {
 								$masterCategory->setImage($fileName);
 								$this->getMasterCategoryTable()->save($masterCategory);
 							}
 						}
 					}
 				}
 				else {
 					$masterCategory->setImage($previousImage);
 					$this->getMasterCategoryTable()->save($masterCategory);
 				}
				
 				return $this->redirect()->toRoute('admin/master_category');
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