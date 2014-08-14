<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Admin\Model\Note;
use Zend\File\Transfer\Adapter\Http  as HttpTransfer;
use Zend\Validator\File\Size;
use Zend\Validator\File\Extension;
use Zend\Escaper\Escaper;
use Admin\Traits\ModuleTablesTrait as AdminTablesTrait;
use Application\ConfigAwareInterface;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Iterator as PaginatorIterator;

class NoteController extends AbstractActionController
implements ConfigAwareInterface
{
	use AdminTablesTrait;
	private $config;

	public function indexAction()
	{

		$page = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : 1;

		$notes = $this->getNoteTable()->fetchAll();
		$paginator = new Paginator(new PaginatorIterator($notes));
		$paginator->setCurrentPageNumber($page)
		->setItemCountPerPage($this->config['pagination']['itempage'])
		->setPageRange($this->config['pagination']['pagerange']);

		return new ViewModel(array(
			'notes' => $paginator,
			'config' => $this->config
			));
	}

	public function addAction()
	{	
		//$escaper = new Escaper('utf-8');
		$jsonModel = new JsonModel();
		$note = new Note();

		//$escaper->escapeHtmlAttr();
		$title = $this->params()->fromPost('title');
		$content = $this->params()->fromPost('content');


		if(!empty($title) && !empty($content)) {
			$note->setTitle($title);
			$note->setContent($content);
			$result = $this->getNoteTable()->save($note);
			$jsonModel->setVariable("result",$result);

		}
		else
			$result = false;

		$jsonModel->setVariable("result",$result);

		return $jsonModel;
	}



	public function deleteAction()
	{
		$jsonModel = new JsonModel();
		$id = (int) $this->params()->fromPost('id');
		
		if($id>0) {
		$result = $this->getNoteTable()->delete($id);
		}
		else
			$result = false;

		$jsonModel->setVariable("result",$result);
		return $jsonModel;

	}

	public function setConfig($config){
		$this->config = $config;
	}
}