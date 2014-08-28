<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Model\Customer;
use Admin\Form\CustomerForm;
use Admin\Traits\ModuleTablesTrait as AdminTablesTrait;
use Admin\Validator\DocumentCompositeKeyValidator;
use Application\ConfigAwareInterface;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Iterator as PaginatorIterator;

class CustomerController extends AbstractActionController
implements ConfigAwareInterface
{
	use AdminTablesTrait;
	private $config;

	public function indexAction()
	{
		$page = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : 1;

		$customer = $this->getCustomerTable()->fetchAll();
		$paginator = new Paginator(new PaginatorIterator($customer));
		$paginator->setCurrentPageNumber($page)
		->setItemCountPerPage($this->config['pagination']['itempage'])
		->setPageRange($this->config['pagination']['pagerange']);

		return new ViewModel(array(
			'customers' => $paginator,
			'config' => $this->config,
			));
	}

	public function addAction()
	{
		$viewModel = new ViewModel();
		$form = $this->getServiceLocator()->get('Admin\Form\CustomerForm');

		$request = $this->getRequest();
		if ($request->isPost()) {
			$customer = new Customer();
			$form->setInputFilter($customer->getInputFilter());
			$form->setData($request->getPost());

			$data = $request->getPost()->toArray();

			$emails = array();

			if(isset($data['emails'])) {
				foreach(array_filter($data['emails']) as $email) {
					if (preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email)) {
						$emails[] = $email; 
					}
				}
				$data['emails'] = json_encode($emails) ;
			}

			if(isset($data['addresses'])) {
				$data['addresses'] = json_encode(array_filter($data['addresses']));
			}
			if(isset($data['phones'])) {
				$data['phones'] = json_encode(array_filter($data['phones']));
			}

			$document = $data['identification'];
			$documentTypeId = $data['identification_type'];
			$classifications = $data['classification'];

			$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
			$documentCompositeKeyValidator = new DocumentCompositeKeyValidator(array(
				'adapter' => $dbAdapter,
				'documentTypeId' => $documentTypeId
				));

			$documentCompositeKeyValidatorResult = !$documentCompositeKeyValidator->isValid($document);

			$form->setData($data);

			if ($form->isValid() && $documentCompositeKeyValidatorResult) {

				$customer->exchangeArray($form->getData());
				$customerId = $this->getCustomerTable()->save($customer);
				$this->getCustomerClassificationTable()->save($customerId,$classifications);

				return $this->redirect()->toRoute('admin/customer');
			}
			else {
				$viewModel->setVariable('emails',json_decode($data['emails']));
				$viewModel->setVariable('addresses',json_decode($data['addresses']));
				$viewModel->setVariable('phones',json_decode($data['phones']));

				$form->get("identification")->setMessages(array(
					'error' =>$documentCompositeKeyValidator->getMessage()
					));
			}
		}
		$viewModel->setVariables(array(
			'form' => $form,
			'config' => $this->config
			));

		return $viewModel;
	}

	public function editAction()
	{
		$viewModel = new ViewModel();

		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('admin/customer', array(
				'action' => 'add'
				));
		}
		$customer = $this->getCustomerTable()->get($id);

		$form = $this->getServiceLocator()->get('Admin\Form\CustomerForm');
		$form->bind($customer);

		$form->get("first_name")->setValue($customer->getFirstName());
		$form->get("last_name")->setValue($customer->getLastName());
		$form->get("identification_type")->setValue($customer->getIdentificationType());

		$emails = $customer->getEmails();
		$addresses = $customer->getAddresses();
		$phones = $customer->getPhones();

		$viewModel->setVariable('emails',json_decode($emails));
		$viewModel->setVariable('addresses',json_decode($addresses));
		$viewModel->setVariable('phones',json_decode($phones));

		$customerClassificationValues = $this->getCustomerClassificationTable()->get($customer->getId());

		$form->get("classification")->setValue($customerClassificationValues);

		
		$request = $this->getRequest();
		if ($request->isPost()) {

			$data = $request->getPost()->toArray();

			$emails = array();

			if(isset($data['emails'])) {
				foreach(array_filter($data['emails']) as $email) {
					if (preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email)) {
						$emails[] = $email; 
					}
				}
				$data['emails'] = json_encode($emails) ;
			}

			if(isset($data['addresses'])) {
				$data['addresses'] = json_encode(array_filter($data['addresses']));
			}
			if(isset($data['phones'])) {
				$data['phones'] = json_encode(array_filter($data['phones']));
			}

			$document = $data['identification'];
			$documentTypeId = $data['identification_type'];
			$classifications = $data['classification'];

			$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
			$documentCompositeKeyValidator = new DocumentCompositeKeyValidator(array(
				'adapter' => $dbAdapter,
				'documentTypeId' => $documentTypeId
				));
			$documentCompositeKeyValidatorResult = true;

			if($customer->getIdentification() !== $document || $documentTypeId !== $customer->getIdentificationType()) {
				$documentCompositeKeyValidatorResult = !$documentCompositeKeyValidator->isValid($document);
			}

			$form->setInputFilter($customer->getInputFilter());
			$form->setData($data);

			if ($form->isValid()) {

				$customerId = $this->getCustomerTable()->save($form->getData());
				$this->getCustomerClassificationTable()->save($customerId,$classifications);

				return $this->redirect()->toRoute('admin/customer');
			}
			else {
				$viewModel->setVariable('emails',json_decode($data['emails']));
				$viewModel->setVariable('addresses',json_decode($data['addresses']));
				$viewModel->setVariable('phones',json_decode($data['phones']));
				
				$form->get("identification")->setMessages(array(
					'error' =>$documentCompositeKeyValidator->getMessage()
					));
			}

		}
		$viewModel->setVariables(array(
			'id' => $id,
			'form' => $form,
			'config' => $this->config,
			));

		return $viewModel;
	}

	public function deleteAction()
	{
		$viewModel = new ViewModel();

		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('admin/customer');
		}
		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost('del', 'No');
			if ($del == 'Si') {
				$id = (int) $request->getPost('id');
				$result = $this->getCustomerTable()->delete($id);

				if(isset($result) && $result) {
					return $this->redirect()->toRoute('admin/customer');
				}
				else {
					$viewModel->setVariable("error",true);
				}
			}
			else
				return $this->redirect()->toRoute('admin/customer');
		}
		$viewModel->setVariables(array(
			'id'=> $id,
			'customer' => $this->getCustomerTable()->get($id),
			'config' => $this->config,
			));

		return $viewModel;
	}

	public function setConfig($config)
	{
		$this->config = $config;
	}
}