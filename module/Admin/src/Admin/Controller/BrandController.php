<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\File\Transfer\Adapter\Http  as HttpTransfer;
use Zend\Validator\File\Size;
use Zend\Validator\File\Extension;
use Admin\Model\Brand;
use Admin\Form\BrandForm;
use Admin\Traits\ModuleTablesTrait as AdminTablesTrait;
use Application\ConfigAwareInterface;

class BrandController extends AbstractActionController
implements ConfigAwareInterface
{
	use AdminTablesTrait;
	private $config;

	public function indexAction()
	{
		return new ViewModel(array(
				'brands' => $this->getBrandTable()->fetchAll(),
				'config' => $this->config
		));
	}

	public function addAction()
	{
		$form = new BrandForm();
		$request = $this->getRequest();

		if ($request->isPost()) {

			$brand = new Brand();
			$form->setInputFilter($brand->getInputFilter());

			$files 		= $request->getPost()->toArray();

			$image    			= $this->params()->fromFiles('image');
			$backgroundImage    = $this->params()->fromFiles('background_image');

			$data 				= array_merge($files,array('image'=> $image['name'],'background_image'=> $backgroundImage['name']));

			$form->setData($data);

			if ($form->isValid()) {
				if(!file_exists($this->config['component']['brand']['image_path']))
					mkdir($this->config['component']['brand']['image_path']);

				$upload = new HttpTransfer();

				$size = new Size($this->config['file_characteristics']['image']['size']);
				$extension = new Extension($this->config['file_characteristics']['image']['extension']);

				$upload->setValidators(array($size,$extension));
				$upload->setDestination($this->config['component']['brand']['image_path']);

				foreach($upload->getFileInfo() as $file => $fileInfo) {

					$fileName = uniqid().'.'.pathinfo($fileInfo['name'], PATHINFO_EXTENSION) ;
					$upload->addFilter('File\Rename', array(
						'target' => $this->config['component']['brand']['image_path'].'/'.$fileName.'',
						'overwrite' => true,
						));

				    if ($upload->isUploaded($file)) {
				        if ($upload->isValid($file)) {
				            if ($upload->receive($file)) {
				                $info = $upload->getFileInfo($file);
				                $tmp  = $info[$file]['tmp_name'];
				                $name  = $info[$file]['name'];
				                $data[$file] = $name ;
				            }
				        }
				    }
				}

				$brand->exchangeArray($data);
				$this->getBrandTable()->save($brand);

				return $this->redirect()->toRoute('admin/brand');
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
		
		if (!$id)
			return $this->redirect()->toRoute('admin/brand', array('action' => 'add'));
		
		$brand = $this->getBrandTable()->get($id);
		$previousImage = $brand->getImage();
		$previousBackgroundImage = $brand->getBackgroundImage();
			
		$form  = new BrandForm();
		
		$form->get("name")->setValue($brand->getName());
		$form->get("description")->setValue($brand->getDescription());
			
		$request = $this->getRequest();
		if ($request->isPost()) {
			
			$form->setInputFilter($brand->getInputFilter());
				
			$files 				= $request->getPost()->toArray();
			$image    			= $this->params()->fromFiles('image');
			$backgroundImage    = $this->params()->fromFiles('background_image');
			$data 				= array_merge($files,array('image'=> $image['name'],'background_image'=> $backgroundImage['name']));

			$form->setData($data);
			
			if ($form->isValid()) {

				$upload = new HttpTransfer();

				$size = new Size($this->config['file_characteristics']['image']['size']);
				$extension = new Extension($this->config['file_characteristics']['image']['extension']);

				$upload->setValidators(array($size,$extension));
				$upload->setDestination($this->config['component']['brand']['image_path']);


				foreach($upload->getFileInfo() as $file => $fileInfo) {

					$fileName = uniqid().'.'.pathinfo($fileInfo['name'], PATHINFO_EXTENSION) ;
					$upload->addFilter('File\Rename', array(
						'target' => $this->config['component']['brand']['image_path'].'/'.$fileName.'',
						'overwrite' => true,
						));

				    if ($upload->isUploaded($file)) {
				        if ($upload->isValid($file)) {
				            if ($upload->receive($file)) {
				                $info = $upload->getFileInfo($file);
				                $tmp  = $info[$file]['tmp_name'];
				                $name  = $info[$file]['name'];
				                $data[$file] = $name ;
				            }
				        }
				    }
				}

				$brand->setName($request->getPost('name'));
				$brand->setDescription($request->getPost('description'));

				if($data['image']) {
					$brand->setImage($data['image']);
					unlink($this->config['component']['brand']['image_path']."/".$previousImage);
				}

				if($data['background_image']) {
					$brand->setBackgroundImage($data['background_image']);
					unlink($this->config['component']['brand']['image_path']."/".$previousBackgroundImage);
				}

				$this->getBrandTable()->save($brand);
				return $this->redirect()->toRoute('admin/brand');
			}
		}

		return array(
				'id' => $id,
				'image' => $previousImage,
				'backgroundImage' => $previousBackgroundImage,
				'form' => $form,
				'config' => $this->config,
		);
	}

		public function deleteAction()
		{
			$id = (int) $this->params()->fromRoute('id', 0);
			if (!$id) {
				return $this->redirect()->toRoute('admin/brand');
			}
			$request = $this->getRequest();
			if ($request->isPost()) {
				$del = $request->getPost('del', 'No');
				if ($del == 'Si') {
					$id = (int) $request->getPost('id');

					$brandTable = $this->getBrandTable()->get($id);

					unlink($this->config['component']['brand']['image_path']."/".$brandTable->getImage());
					unlink($this->config['component']['brand']['image_path']."/".$brandTable->getBackgroundImage());
					
					$this->getBrandTable()->delete($id);
				}

				return $this->redirect()->toRoute('admin/brand');
			}
			return array(
					'id'=> $id,
					'config' => $this->config,
					'brand' => $this->getBrandTable()->get($id)
			);
		}

	public function setConfig($config){
		$this->config = $config;
	}
}