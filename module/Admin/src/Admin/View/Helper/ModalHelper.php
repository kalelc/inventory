<?php
namespace Admin\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;

class ModalHelper extends AbstractHelper
{
	protected $serviceLocator;

	public function __invoke()
	{
		return $this;
	}

	public function __construct($serviceLocator)
	{
		$this->serviceLocator = $serviceLocator ;
	}

	public function image()
	{
		$viewModel = new ViewModel();
		$viewModel->setTemplate('admin/helper/modal/image');
		
		return $this->getView()->render($viewModel);
	}

	public function listContent()
	{
		$viewModel = new ViewModel();
		$viewModel->setTemplate('admin/helper/modal/list');
		
		return $this->getView()->render($viewModel);
	}

	public function notes()
	{
		$viewModel = new ViewModel();
		$viewModel->setTemplate('admin/helper/modal/notes');
		
		return $this->getView()->render($viewModel);

	}

	public function userAdd()
	{
		$viewModel = new ViewModel();
		$viewModel->setTerminal(false);
		
		$form = $this->serviceLocator->get('Admin\Form\CustomerForm');
		$config = $this->serviceLocator->get('config');

		$viewModel->setTemplate('admin/helper/modal/customer/add');


		$viewModel->setVariables(array(
			'form' => $form,
			'config' => $config
			));

		return $this->getView()->render($viewModel);
	}

}