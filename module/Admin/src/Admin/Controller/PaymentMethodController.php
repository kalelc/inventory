<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Model\PaymentMethod;
use Admin\Form\PaymentMethodForm;
use Admin\Traits\ModuleTablesTrait as AdminTablesTrait;
use Application\ConfigAwareInterface;

class PaymentMethodController extends AbstractActionController
implements ConfigAwareInterface
{
	use AdminTablesTrait;
	private $config;
	
	public function indexAction()
	{
		return new ViewModel(array(
			'paymentsMethods' => $this->getPaymentMethodTable()->fetchAll(),
			'config' => $this->config
			));
	}

	public function addAction()
	{
		$config = $this->getServiceLocator()->get("config");
		
		$form = new PaymentMethodForm();
		$form->get('submit')->setValue('guardar');

		$request = $this->getRequest();
		if ($request->isPost()) {
			$paymentMethod = new PaymentMethod();
			$form->setInputFilter($paymentMethod->getInputFilter());
			$form->setData($request->getPost());

			if ($form->isValid()) {

				$paymentMethod->exchangeArray($form->getData());
				$this->getPaymentMethodTable()->save($paymentMethod);

				return $this->redirect()->toRoute('admin/payment_method');
			}
		}
		return array(
			'form' => $form,
			'config' => $this->config
			);
	}


	public function editAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('admin/payment_method', array(
				'action' => 'add'
				));
		}
		$paymentMethod = $this->getPaymentMethodTable()->get($id);

		$form  = new paymentMethodForm();
		$form->bind($paymentMethod);
		
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setInputFilter($paymentMethod->getInputFilter());
			$form->setData($request->getPost());
			
			if ($form->isValid()) {

				$this->getPaymentMethodTable()->save($form->getData());

				return $this->redirect()->toRoute('admin/payment_method');
			}
		}

		return array(
			'id' => $id,
			'form' => $form,
			'config' => $this->config
			);
	}

	public function deleteAction()
	{
		$viewModel = new ViewModel();
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('admin/payment_method');
		}
		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost('del', 'No');
			if ($del == 'Si') {
				$id = (int) $request->getPost('id');

				$result = $this->getPaymentMethodTable()->delete($id);

				if(isset($result) && $result) {
					return $this->redirect()->toRoute('admin/payment_method');
				}
				else {
					$viewModel->setVariable("error",true);
				}
			}
			else {
				return $this->redirect()->toRoute('admin/payment_method');
			}
		}
		$viewModel->setVariables(array(
			'id'=> $id,
			'paymentMethod' => $this->getPaymentMethodTable()->get($id),
			'config' => $this->config
			));
		return $viewModel; 
	}

	public function setConfig($config)
	{
		$this->config = $config;
	}
}