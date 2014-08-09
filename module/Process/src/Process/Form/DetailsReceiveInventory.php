<?php
namespace Process\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class DetailsReceiveInventoryForm extends Form
{
	private $customers;
	private $paymentMethods;
	private $shipments;

	public function __construct($customers,$paymentMethods,$shipments)
	{
		parent::__construct('receive_inventory');
		$this->setAttribute('method', 'post');
		$this->setAttribute('class', 'form-horizontal');
		$this->setAttribute('enctype','multipart/form-data');

		$this->customers = $customers;
		$this->paymentMethods = $paymentMethods;
		$this->shipments = $shipments;


		$this->add(array(
			'name' => 'id',
			'attributes' => array(
				'type'  => 'hidden',
				),
			));

		$this->add(array(
			'name' => 'register_date',
			'attributes' => array(
				'type'  => 'text',
				'class' => 'form-control',
				'readonly' => 'readonly',
				'value' => date('d-m-Y')
				),
			'options' => array(
				'label' => 'name',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),

				),
			));

		$this->add(array(
			'type' => 'select',
			'name' => 'customer',
			'options' => array(
				'label' => 'customer',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
				'value_options' => $this->customers,
				'empty_option' => 'seleccione una opción',
				'disable_inarray_validator' => true,
				),
			'attributes' => array(
				'class' => 'form-control',
				'data-live-search' => 'true'
				)
			));


		$this->add(array(
			'type' => 'select',
			'name' => 'payment_method',
			'options' => array(
				'label' => 'payment_method',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
				'value_options' => $this->paymentMethods,
				'empty_option' => 'seleccione una opción',
				'disable_inarray_validator' => true,
				),
			'attributes' => array(
				'class' => 'form-control',
				)
			));

		$this->add(array(
			'type' => 'select',
			'name' => 'shipment',
			'options' => array(
				'label' => 'shipment',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
				'value_options' => $this->shipments,
				'empty_option' => 'seleccione una opción',
				'disable_inarray_validator' => true,
				),
			'attributes' => array(
				'class' => 'form-control',
				)
			));

		$this->add(array(
			'name' => 'guide_number',
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
			'name' => 'invoice',
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
			'name' => 'invoice_file',
			'attributes' => array(
				'type'  => 'file',
				),
			'options' => array(
				'label' => 'invoice_file',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
				),
			)); 

		$this->add(array(
			'name' => 'observation',
			'attributes' => array(
				'type'  => 'textarea',
				'class' => 'form-control',
				),
			'options' => array(
				'label' => 'observation',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
				),
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