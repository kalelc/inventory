<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Traits\ModuleTablesTrait as AdminTablesTrait;
use Security\Traits\SecurityTrait as SecurityTrait;
use Application\ConfigAwareInterface;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Iterator as PaginatorIterator;
use Zend\Authentication\AuthenticationService;

class DashboardController extends AbstractActionController
implements ConfigAwareInterface
{
	use AdminTablesTrait,SecurityTrait;
	private $config;

	public function indexAction()
	{	
		$authenticationService = new AuthenticationService();
		$user = $authenticationService->getStorage()->read();
		
		$notes = $this->getNoteTable()->fetchAll($user->id);
		$logs = $this->getLogTable()->fetchAll($user->id);
		return new ViewModel(array(
			'config' => $this->config,
			'notes' => $notes,
			'logs' => $logs,
			));
	}

	public function setConfig($config){
		$this->config = $config;
	}
}