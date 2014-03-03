<?php
namespace Admin\View\Helper;
 
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;

class ModalHelper extends AbstractHelper
{
    public function __invoke()
    {
    $viewModel = new ViewModel();
   	$viewModel->setTemplate('admin/helper/modal');
    
    return $this->getView()->render($viewModel);
    }
}