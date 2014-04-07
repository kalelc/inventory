<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\File\Transfer\Adapter\Http  as HttpTransfer;
use Zend\Validator\File\Size;
use Zend\Validator\File\Extension;
use Zend\Escaper\Escaper;

use Admin\Model\Product;
use Admin\Form\ProductForm;
use Admin\Traits\ModuleTablesTrait as AdminTablesTrait;
use Application\ConfigAwareInterface;

use Zend\Http\Client as HttpClient;

class ProductController extends AbstractActionController 
implements ConfigAwareInterface
{
	use AdminTablesTrait;
	private $config;

	public function indexAction()
	{
		return new ViewModel(array(
			'products' => $this->getProductTable()->fetchAll(),
			'config' => $this->config,
			));
	}

//pendiente edicion de productos

	public function addAction()
	{
		$form = $this->getServiceLocator()->get('Admin\Form\ProductForm');
		$request = $this->getRequest();

		if ($request->isPost()) {

			$product = new Product();
			$form->setInputFilter($product->getInputFilter());

			$data 		= $request->getPost()->toArray();

			$form->setData($data);

			if ($form->isValid()) {


				$fileService = $this->getServiceLocator()->get('Admin\Service\FileService');

				$fileService->setDestination($this->config['component']['product']['image_path']);
				$fileService->setSize($this->config['file_characteristics']['image']['size']);
				$fileService->setExtension($this->config['file_characteristics']['image']['extension']);

				$image1 = $fileService->copy($this->params()->fromFiles('image1'));
				$image2 = $fileService->copy($this->params()->fromFiles('image2'));
				$image3 = $fileService->copy($this->params()->fromFiles('image3'));
				$image4 = $fileService->copy($this->params()->fromFiles('image4'));
				$image5 = $fileService->copy($this->params()->fromFiles('image5'));
				$image6 = $fileService->copy($this->params()->fromFiles('image6'));

				$fileService->setDestination($this->config['component']['product']['file_path']);
				$fileService->setSize($this->config['file_characteristics']['file']['size']);
				$fileService->setExtension($this->config['file_characteristics']['file']['extension']);

				$specificationFile 	= $fileService->copy($this->params()->fromFiles('specification_file'));
				$manualFile 		= $fileService->copy($this->params()->fromFiles('manual_file'));

				$fileService->setDestination($this->config['component']['product']['video_path']);
				$fileService->setSize($this->config['file_characteristics']['video']['size']);
				$fileService->setExtension($this->config['file_characteristics']['video']['extension']);

				$video				= $fileService->copy($this->params()->fromFiles('video'));

				$data['specification_file'] = $specificationFile ;
				$data['manual_file'] 		= $manualFile ;
				$data['video'] 				= $video ;
				$data['image1'] 			= $image1 ;
				$data['image2'] 			= $image2 ;
				$data['image3'] 			= $image3 ;
				$data['image4'] 			= $image4 ;
				$data['image5'] 			= $image5 ;
				$data['image6'] 			= $image6 ;

				$product->exchangeArray($data);
				$this->getProductTable()->save($product);

				return $this->redirect()->toRoute('admin/product');
			}
		}	
		return array(
			'form' => $form,
			'config' => $this->config
			);
	}

	public function getSpecificationToCategoryAction()
	{
		$jsonModel = new JsonModel();

        //$category            = $this->params()->fromPost('category');
		$category = 4;
		$result =  $this->getCategorySpecificationTable()->getCategorySpecificationCheckValue($category);

		dump($jsonModel);
		dumpx($result);


		$jsonModel->setVariable("specification",$specification);

		return $jsonModel;
	}

	/*prueba de metodo para definir los permisos en todos los archivos*/
	public function editAction()
	{
		dump("get config");
		dumpx($this->config);
		foreach($this->config['component'] as $component)
		{
			if(@$component['image_path'] || @$component['video_path'] || @$component['file_path']) {
				if(isset($component['image_path']) && !empty($component['image_path'])) {
					if(!file_exists($component['image_path'])) {
						$oldmask = umask(0);
						mkdir($component['image_path'], 0777);
						umask($oldmask);
						echo $component['image_path']."<br>" ;
					}
				}
				if(isset($component['video_path']) && !empty($component['video_path'])) {
					if(!file_exists($component['video_path'])) {
						$oldmask = umask(0);
						mkdir($component['video_path'], 0777);
						umask($oldmask);
						echo $component['video_path']."<br>" ;
						
					}
				}
				if(isset($component['file_path']) && !empty($component['file_path'])) {
					if(!file_exists($component['file_path'])) {
						$oldmask = umask(0);
						mkdir($component['file_path'], 0777);
						umask($oldmask);
						echo $component['file_path']."<br>" ;
					}
				}
				//echo @$component['video_path']."<br>";
				//echo @$component['file_path']."<br>";
			}
		}

		exit();
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('admin/product', array(
				'action' => 'add'
				));
		}
		$product = $this->getProductTable()->get($id);

		$form  = new ProductForm();
		$form->bind($product);
		
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setInputFilter($product->getInputFilter());
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$this->getProductTable()->save($form->getData());

				return $this->redirect()->toRoute('admin/product');
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
			return $this->redirect()->toRoute('admin/product');
		}
		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost('del', 'No');
			if ($del == 'Si') {
				$id = (int) $request->getPost('id');
				
				$productTable = $this->getProductTable()->get($id);

				$fileService = $this->getServiceLocator()->get('Admin\Service\FileService');
				$fileService->setDestination($this->config['component']['product']['image_path']);

				$fileService->delete($productTable->getImage1());
				$fileService->delete($productTable->getImage2());
				$fileService->delete($productTable->getImage3());
				$fileService->delete($productTable->getImage4());
				$fileService->delete($productTable->getImage5());
				$fileService->delete($productTable->getImage6());

				$fileService->setDestination($this->config['component']['product']['file_path']);

				$fileService->delete($productTable->getSpecificationFile());
				$fileService->delete($productTable->getManualFile());

				$fileService->setDestination($this->config['component']['product']['video_path']);

				$fileService->delete($productTable->getVideo());

				$this->getProductTable()->delete($id);
			}

			return $this->redirect()->toRoute('admin/product');
		}
		return array(
			'id'=> $id,
			'product' => $this->getProductTable()->get($id),
			'config' => $this->config,
			);
	}

	public function setConfig($config)
	{
		$this->config = $config;
	}
}