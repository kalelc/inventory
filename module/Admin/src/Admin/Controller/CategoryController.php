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
		$request = $this->getRequest();

		if ($request->isPost()) {


			$category = new Category();
			$form->setInputFilter($category->getInputFilter());

			$nonFile = $request->getPost()->toArray();
			$file    = $this->params()->fromFiles('image');
			$data = array_merge(
				$nonFile,
				array('image'=> $file['name'])
				);

			$form->setData($data);
			
			if ($form->isValid()) {

				if(!file_exists($this->config['component']['category']['image_path']))
					mkdir($this->config['component']['category']['image_path']);

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
						$adapter->setDestination($this->config['component']['category']['image_path']);

						foreach ($adapter->getFileInfo() as $info) {
							$fileName = time().'.'.pathinfo($info['name'], PATHINFO_EXTENSION) ;

							$adapter->addFilter('File\Rename',
								array('target' => $adapter->getDestination().'/'.$fileName.'',
									'overwrite' => true));

							if ($adapter->receive($info['name'])) {

								$category->exchangeArray($form->getData());
								$category->setImage($fileName);

								$categoryId = $this->getCategoryTable()->save($category);

								$categoryData = $form->getData();
								$this->getCategorySerialNameTable()->save($categoryId,$categoryData['serial_name']);
								$this->getCategorySpecificationTable()->save($categoryId,$categoryData['specification']);


								return $this->redirect()->toRoute('category');
							}
						}
					}
				}
				else {

					$category->exchangeArray($form->getData());
					$categoryId = $this->getCategoryTable()->save($category);

					$categoryData = $form->getData();
					$this->getCategorySerialNameTable()->save($categoryId,$categoryData['serial_name']);
					$this->getCategorySpecificationTable()->save($categoryId,$categoryData['specification']);

					return $this->redirect()->toRoute('category');
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
			return $this->redirect()->toRoute('category', array(
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
			$data = array_merge(
				$nonFile,
				array('image'=> $file['name'])
				);

			$form->setData($data);

			if ($form->isValid()) {

				$categoryData = $form->getData();
				
				$size = new Size($this->config['file_characteristics']['image']['size']);
				$extension= new Extension($this->config['file_characteristics']['image']['extension']);

				$image = $form->get('image')->getValue() ;

				if(!file_exists($this->config['component']['category']['image_path'])){
					mkdir($this->config['component']['category']['image_path']);
				}
				
				$newImage = empty($data['image']) ? $previousImage : $data['image'] ;
				
				$category->setMasterCategory($categoryData["master_category"]);
				$category->setSingularName($categoryData["singular_name"]);
				$category->setPluralName($categoryData["plural_name"]);
				$category->setImage($newImage);
				$category->setShippingCost($categoryData["shipping_cost"]);
				$category->setAdditionalShipping($categoryData["additional_shipping"]);
				$category->setDescription($categoryData["description"]);

				if(!empty($image)){
					unlink($this->config['component']['category']['image_path']."/".$previousImage);

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
						$adapter->setDestination($this->config['component']['category']['image_path']);

						foreach ($adapter->getFileInfo() as $info) {
							$fileName = time().'.'.pathinfo($info['name'], PATHINFO_EXTENSION) ;

							$adapter->addFilter('File\Rename',
								array('target' => $adapter->getDestination().'/'.$fileName.'',
									'overwrite' => true));

							if ($adapter->receive($info['name'])) {
								$category->setImage($fileName);
								$this->getCategoryTable()->save($category);

								$categoryData = $form->getData();
								$this->getCategorySerialNameTable()->delete($category->getId());
								$this->getCategorySpecificationTable()->delete($category->getId());
							}
						}
					}
				}
				else {
					$category->setImage($previousImage);
					$this->getCategoryTable()->save($category);

					$categoryData = $form->getData();
					$this->getCategorySerialNameTable()->delete($category->getId());
					$this->getCategorySpecificationTable()->delete($category->getId());
					
					$this->getCategorySerialNameTable()->save($category->getId(),$categoryData['serial_name']);
					$this->getCategorySpecificationTable()->save($category->getId(),$categoryData['specification']);
				}

				return $this->redirect()->toRoute('category');
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
			return $this->redirect()->toRoute('category');
		}
		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost('del', 'No');
			if ($del == 'Si') {
				$id = (int) $request->getPost('id');
				
				@unlink($this->config['component']['category']['image_path']."/".$this->getCategoryTable()->get($id)->getImage());
				
				$this->getCategorySerialNameTable()->delete($id);
				$this->getCategorySpecificationTable()->delete($id);
				$this->getCategoryTable()->delete($id);
			}

			return $this->redirect()->toRoute('category');
		}
		return array(
			'id'=> $id,
			'config' => $this->config,
			'category' => $this->getCategoryTable()->get($id)
			);
	}

	public function setConfig($config)
	{
		$this->config = $config;
	}
}