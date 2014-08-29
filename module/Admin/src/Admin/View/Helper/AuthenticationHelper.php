<?php
namespace Admin\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;

use Admin\Form\NoteForm;
use Application\ConfigAwareInterface;

class AuthenticationHelper extends AbstractHelper
implements ConfigAwareInterface
{
	private $config;

	protected $serviceLocator;

	public function __invoke()
	{
		return $this;
	}

	public function __construct($config)
	{
		$this->config = $config;
	}

	public function menu()
	{
		$resources = $this->config->get("config")['resources'];
		$components = $this->config->get('config')['component'];

		$viewModel = new ViewModel();
		$viewModel->setTemplate('admin/helper/menu');
		$viewModel->setVariables(array(
			'resources' => $resources,
			'components' => $components
			));
		
		return $this->getView()->render($viewModel);
	}

	public function setConfig($config)
	{
		$this->config = $config;
	}
}