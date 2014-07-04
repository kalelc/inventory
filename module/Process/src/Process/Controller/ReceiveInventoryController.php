<?php
namespace Process\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Process\Model\ReceiveInventory;
use Zend\File\Transfer\Adapter\Http  as HttpTransfer;
use Zend\Validator\File\Size;
use Zend\Validator\File\Extension;
use Process\Form\ReceiveInventoryForm;
use Process\Traits\ModuleTablesTrait as ProcessTablesTrait;
use Application\ConfigAwareInterface;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Iterator as PaginatorIterator;

class ReceiveInventoryController extends AbstractActionController
implements ConfigAwareInterface
{
	//use ProcessTablesTrait;
	private $config;

	public function indexAction()
	{
		dumpx("index");
	}

	public function addAction()
	{
		dumpx("add");
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



	public function setConfig($config){
		$this->config = $config;
	}
}