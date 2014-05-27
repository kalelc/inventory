<?php
namespace Admin\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;

class ModalHelper extends AbstractHelper
{
	public function __invoke()
	{
		return $this;
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

}