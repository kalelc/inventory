<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\File\Transfer\Adapter\Http  as HttpTransfer;
use Zend\Validator\File\Size;
use Zend\Validator\File\Extension;
use Zend\Escaper\Escaper;
use Zend\Json\Json;

use Admin\Model\Product;
use Admin\Form\ProductForm;
use Admin\Traits\ModuleTablesTrait as AdminTablesTrait;
use Application\ConfigAwareInterface;

use Zend\Http\Client as HttpClient;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Iterator as PaginatorIterator;

class ProductController extends AbstractActionController 
implements ConfigAwareInterface
{
	use AdminTablesTrait;
	private $config;

	public function indexAction()
	{
		$page = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : 1;

		$product = $this->getProductTable()->fetchAll();
		$paginator = new Paginator(new PaginatorIterator($product));
		$paginator->setCurrentPageNumber($page)
		->setItemCountPerPage($this->config['pagination']['itempage'])
		->setPageRange($this->config['pagination']['pagerange']);

		return new ViewModel(array(
			'products' => $paginator,
			'config' => $this->config,
			));
	}

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

				$data['specification_file'] = $specificationFile ? $specificationFile : "" ;
				$data['manual_file'] 		= $manualFile ? $manualFile : "" ;
				$data['image1'] 			= $image1 ? $image1 : "" ;
				$data['image2'] 			= $image2 ? $image2 : "" ;
				$data['image3'] 			= $image3 ? $image3 : "" ;
				$data['image4'] 			= $image4 ? $image4 : "" ;
				$data['image5'] 			= $image5 ? $image5 : "" ;
				$data['image6'] 			= $image6 ? $image6 : "" ;

				$measures = $data['measures'];
				$apps = $data['apps'];

				$product->exchangeArray($data);
				$productId = $this->getProductTable()->save($product);

				$productMeasuresTable = $this->getProductMeasureTable();
				$productMeasuresTable->save($productId,$measures);

				$productAppTable = $this->getProductAppTable();
				$productAppTable->save($productId,$apps);

				return $this->redirect()->toRoute('admin/product');
			}
			else {
				$select = $form->get("measures")->setValue($data['measures']);
			}
		}

		return array(
			'form' => $form,
			'config' => $this->config,
			//'select ' => $select
			);
	}

	public function getSpecificationToCategoryAction()
	{
		$category = $this->params()->fromPost('category');
		$jsonModel = new JsonModel();
		$listSpecifications = $this->getCategorySpecificationTable()->getCategorySpecificationCheckValue($category);


		$specification = array();
		$measure = array();

		foreach($listSpecifications as $listSpecification) {

			$listMeasures = $this->getMeasureTable()->getBySpecification($listSpecification->getSpecification());

			foreach($listMeasures as $listMeasure) {

				$measure[$listMeasure->getId()] = $listMeasure->getMeasureValue()." ".$listMeasure->getMeasureTypeName();
			}

			$specification[] = array(
				"name"  =>$listSpecification->getSpecificationName(),
				"image" => $listSpecification->getSpecificationImage(),
				"measure" => $measure
				);

			$measure = array();
		}


		$jsonModel->setVariable("specification",$specification);
		return $jsonModel;
	}

	public function getSpecificationCategory($category)
	{
		error_log("category");
		error_log($category);
		$jsonModel = new JsonModel();
		$listSpecifications = $this->getCategorySpecificationTable()->getCategorySpecificationCheckValue($category);


		$specification = array();
		$measure = array();

		foreach($listSpecifications as $listSpecification) {

			$listMeasures = $this->getMeasureTable()->getBySpecification($listSpecification->getSpecification());

			foreach($listMeasures as $listMeasure) {

				$measure[$listMeasure->getId()] = $listMeasure->getMeasureValue()." ".$listMeasure->getMeasureTypeName();
			} 

			$specification[] = array(
				"name"  =>$listSpecification->getSpecificationName(),
				"image" => $listSpecification->getSpecificationImage(),
				"measure" => $measure
				);

			$measure = array();
		}


		$jsonModel->setVariable("specification",$specification);
		return $jsonModel;

	}

	public function editAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('admin/product', array('action' => 'add'));
		}

		$product = $this->getProductTable()->get($id);

		$previousManualFile  		= $product->getManualFile();
		$previousSpecificationFile  = $product->getSpecificationFile();
		$previousImage1 			= $product->getImage1();
		$previousImage2 			= $product->getImage2();
		$previousImage3 			= $product->getImage3();
		$previousImage4 			= $product->getImage4();
		$previousImage5 			= $product->getImage5();
		$previousImage6 			= $product->getImage6();

		$category = $product->getCategory();

		$form = $this->getServiceLocator()->get("Admin\Form\ProductForm");

		$form->get("upc_bar_code")->setValue($product->getUpcBarCode());
		$form->get("model")->setValue($product->getModel());
		$form->get("brand")->setValue($product->getBrand());
		$form->get("category")->setValue($category);
		$form->get("part_no")->setValue($product->getPartNo());
		$form->get("price")->setValue($product->getPrice());
		$form->get("iva")->setValue($product->getIva());
		$form->get("qty_low")->setValue($product->getQtyLow());
		$form->get("qty_buy")->setValue($product->getQtyBuy());
		$form->get("description")->setValue($product->getDescription());
		$form->get("status")->setValue($product->getStatus());


		$productAppTable = $this->getProductAppTable();
		$appValues = $productAppTable->get($id);
		$form->get("apps")->setValue($appValues);

		$productMeasures = $this->getMeasureTable()->getByProduct($id);

		$request = $this->getRequest();
		if ($request->isPost()) {

			$product->getInputFilter()->get('category')->setRequired(false);
			$product->getInputFilter()->get('measures')->setRequired(false);

			$form->setInputFilter($product->getInputFilter());

			$data = $request->getPost()->toArray();
			$form->setData($data);

			if ($form->isValid()) {

				$product->setUpcBarCode($request->getPost('upc_bar_code'));
				$product->setModel($request->getPost('model'));
				$product->setBrand($request->getPost('brand'));
				$product->setCategory($request->getPost('category'));
				$product->setPartNo($request->getPost('part_no'));
				$product->setPrice($request->getPost('price'));
				$product->setIva($request->getPost('iva'));
				$product->setQtyLow($request->getPost('qty_low'));
				$product->setQtyBuy($request->getPost('qty_buy'));
				$product->setDescription($request->getPost('description'));
				$product->setStatus($request->getPost('status'));
				$product->setVideo($request->getPost('video'));

				/*files*/
				$fileService = $this->getServiceLocator()->get('Admin\Service\FileService');
				$fileService->setDestination($this->config['component']['product']['file_path']);
				$fileService->setSize($this->config['file_characteristics']['file']['size']);
				$fileService->setExtension($this->config['file_characteristics']['file']['extension']);

				$specificationFile = $this->params()->fromFiles('specification_file');
				$manualFile = $this->params()->fromFiles('manual_file');

				if(isset($specificationFile['name']) && !empty($specificationFile['name'])) {
					$specificationFile = $fileService->copy($specificationFile);
					$product->setSpecificationFile($specificationFile);
					if(isset($previousSpecificationFile) && !empty($previousSpecificationFile))
						@unlink($this->config['component']['product']['file_path']."/".$previousSpecificationFile);
				}

				if(isset($manualFile['name']) && !empty($manualFile['name'])) {
					$manualFile = $fileService->copy($manualFile);
					$product->setManualFile($manualFile);
					if(isset($previousManualFile) && !empty($previousManualFile))
						@unlink($this->config['component']['product']['file_path']."/".$previousManualFile);
				}

				/*images*/
				$fileService = $this->getServiceLocator()->get('Admin\Service\FileService');
				$fileService->setDestination($this->config['component']['product']['image_path']);
				$fileService->setSize($this->config['file_characteristics']['image']['size']);
				$fileService->setExtension($this->config['file_characteristics']['image']['extension']);

				$image1 = $this->params()->fromFiles('image1');
				$image2 = $this->params()->fromFiles('image2');
				$image3 = $this->params()->fromFiles('image3');
				$image4 = $this->params()->fromFiles('image4');
				$image5 = $this->params()->fromFiles('image5');
				$image6 = $this->params()->fromFiles('image6');

				if(isset($video['name']) && !empty($video['name'])) {
					$video = $fileService->copy($video);
					$product->setVideo($video);
					if(isset($previousVideo) && !empty($previousVideo))
						@unlink($this->config['component']['product']['image_path']."/".$previousVideo);
				}

				if(isset($image1['name']) && !empty($image1['name'])) {
					$image1 = $fileService->copy($image1);
					$product->setImage1($image1);
					if(isset($previousImage1) && !empty($previousImage1))
						@unlink($this->config['component']['product']['image_path']."/".$previousImage1);
				}

				if(isset($image2['name']) && !empty($image2['name'])) {
					$image2 = $fileService->copy($image2);
					$product->setImage2($image2);
					if(isset($previousImage1) && !empty($previousImage2))
						@unlink($this->config['component']['product']['image_path']."/".$previousImage2);
				}

				if(isset($image3['name']) && !empty($image3['name'])) {
					$image3 = $fileService->copy($image3);
					$product->setImage3($image3);
					if(isset($previousImage3) && !empty($previousImage3))
						@unlink($this->config['component']['product']['image_path']."/".$previousImage3);
				}

				if(isset($image4['name']) && !empty($image4['name'])) {
					$image4 = $fileService->copy($image4);
					$product->setImage4($image4);
					if(isset($previousImage4) && !empty($previousImage4))
						@unlink($this->config['component']['product']['image_path']."/".$previousImage4);
				}

				if(isset($image5['name']) && !empty($image5['name'])) {
					$image5 = $fileService->copy($image5);
					$product->setImage5($image5);
					if(isset($previousImage5) && !empty($previousImage5))
						@unlink($this->config['component']['product']['image_path']."/".$previousImage5);
				}

				if(isset($image6['name']) && !empty($image6['name'])) {
					$image6 = $fileService->copy($image6);
					$product->setImage6($image6);
					if(isset($previousImage6) && !empty($previousImage6))
						@unlink($this->config['component']['product']['image_path']."/".$previousImage6);
				}

				$productId = $this->getProductTable()->save($product);

				$apps = $data['apps'];

				$productAppTable->save($productId,$apps);

				return $this->redirect()->toRoute('admin/product');
			}
		}

		return array(
			'id' 	   			=> $id,
			'specificationFile' => $previousSpecificationFile,
			'manualFile'   		=> $previousManualFile,
			'image1'   			=> $previousImage1,
			'image2'   			=> $previousImage2,
			'image3'   			=> $previousImage3,
			'image4'   			=> $previousImage4,
			'image5'   			=> $previousImage5,
			'image6'   			=> $previousImage6,
			'form' 	   			=> $form,
			'config'   			=> $this->config,
			'productMeasures'	=> $productMeasures
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

				$this->getProductMeasureTable()->delete($id);
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