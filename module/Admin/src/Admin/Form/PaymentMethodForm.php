<?php
namespace Admin\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Admin\Model\BankTable;

class PaymentMethodForm extends Form
{
	private $bankTable;

	public function __construct()
	{
		parent::__construct('payment_method');
		
		$this->setAttribute('method', 'post');
		$this->setAttribute('class', 'form-horizontal');

		$this->add(array(
			'name' => 'id',
			'attributes' => array(
				'type'  => 'hidden',
				),
			));

		$this->add(array(
			'name' => 'name',
			'attributes' => array(
				'type'  => 'text',
				'class' => 'form-control',
				),
			'options' => array(
				'label' => 'name',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),

				),
			));

		$this->add(array(
			'name' => 'description',
			'attributes' => array(
				'type'  => 'textarea',
				'class' => 'form-control',
				),
			'options' => array(
				'label' => 'description',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
				),
			));
		
		$this->add(array(
			'name' => 'bank_info',
			'type' => 'Zend\Form\Element\Checkbox',
			'options' => array(
				'use_hidden_element' => true,
				'checked_value' => 1,
				'unchecked_value' => '',
				'label' => 'bank_info',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
				)
			));


		$this->add(array(
			'type' => 'Zend\Form\Element\Csrf',
			'name' => 'csrf',
			'options' => array(
				'csrf_options' => array(
					'timeout' => 600
					)
				)
			));

		$this->add(array(
			'name' => 'submit',
			'attributes' => array(
				'type'  => 'submit',
				'class' => 'btn btn-primary btn-sm'
				),
			));
	}
}