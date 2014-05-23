<?php
namespace Admin\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;

class LayoutHelper extends AbstractHelper
{
	public function __invoke()
	{
		return $this;
	}

	public function header($values)
	{
		$viewModel = new ViewModel();
		$viewModel->setTemplate('admin/helper/_header');
		$viewModel->setVariable('values',$values);
		return $this->getView()->render($viewModel);
	}

	public function footer($values)
	{
		$viewModel = new ViewModel();
		$viewModel->setTemplate('admin/helper/_footer');
		$viewModel->setVariable('values',$values);
		return $this->getView()->render($viewModel);

	}
}