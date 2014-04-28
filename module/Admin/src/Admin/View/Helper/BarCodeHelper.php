<?php
namespace Admin\View\Helper;
 
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;
use Zend\Barcode\Barcode;

class BarCodeHelper extends AbstractHelper
{
    public function __invoke($code)
    {
	$barcodeOptions = array('text' => '123456789123');
	$rendererOptions = array();
	$image = Barcode::factory(
		'ean13', 'image', $barcodeOptions, $rendererOptions
		);
	$image->render();
	dumpx("barCode");
//    $viewModel = new ViewModel();
//   	$viewModel->setTemplate('admin/helper/modal');
//    return $this->getView()->render($viewModel);
    }
}