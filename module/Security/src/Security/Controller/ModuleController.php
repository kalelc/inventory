<?php
namespace Security\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Security\Model\Modules;
use Security\Traits\SecurityTrait as SecurityTrait;
use Application\ConfigAwareInterface;

class ModuleController extends AbstractActionController implements ConfigAwareInterface
{
	use SecurityTrait;
	private $config;

	public function indexAction()
	{	
		//$modulesTable = $this->getModuleTable()->delete();
		//exit();
		$modulesTable = $this->getModuleTable()->fetchAll();
		if($modulesTable->count()<=0) {
			$modules = array();
			$resources = $this->config['resources'];
			foreach($resources as $resourceIndex => $resourceValue) {
				dump($resourceIndex);
				foreach ($resourceValue as $moduleKey => $moduleValue) {
					$modules[] = $moduleKey;
				}
			}
			$this->getModuleTable()->save($modules);
		}
		else {
			echo "ya existen modulos importados";
		}
		return false;
	}

	public function setConfig($config)
	{
		$this->config = $config;
	}
}
