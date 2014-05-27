<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Model\Customer;
use Admin\Form\CustomerForm;
use Admin\Traits\ModuleTablesTrait as AdminTablesTrait;
use Admin\Validator\DocumentCompositeKeyValidator;
use Application\ConfigAwareInterface;

class CustomerController extends AbstractActionController
implements ConfigAwareInterface
{
	use AdminTablesTrait;
	private $config;

	public function indexAction()
	{
		return new ViewModel(array(
			'customers' => $this->getCustomerTable()->fetchAll(),
			'config' => $this->config,
			));
	}

	public function addAction()
	{
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

			$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
			$documentCompositeKeyValidator = new DocumentCompositeKeyValidator(array(
				'adapter' => $dbAdapter,
				'documentTypeId' => $documentTypeId
				));

			$documentCompositeKeyValidatorResult = !$documentCompositeKeyValidator->isValid($document);

			$form->setData($data);
			if ($form->isValid() && $documentCompositeKeyValidatorResult) {

				$customer->exchangeArray($form->getData());
				$this->getCustomerTable()->save($customer);

				return $this->redirect()->toRoute('admin/customer');
			}
		}
		return array(
			'form' => $form,
			'config' => $this->config);
	}


	public function editAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('admin/customer', array(
				'action' => 'add'
				));
		}
		$customer = $this->getCustomerTable()->get($id);

		$form  = new CustomerForm();
		$form->bind($customer);
		
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setInputFilter($customer->getInputFilter());
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$this->getCustomerTable()->save($form->getData());

				return $this->redirect()->toRoute('admin/customer');
			}
		}
		return array(
			'id' => $id,
			'form' => $form,
			'config' => $this->config,
			);
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