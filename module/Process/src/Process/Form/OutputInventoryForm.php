<?php
namespace Process\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class OutputInventoryForm extends Form
{
	private $clients;
	private $paymentMethods;
	private $sellers;

	public function __construct($clients,$paymentMethods,$sellers)
	{
		parent::__construct('output_inventory');
		$this->setAttribute('method', 'post');
		$this->setAttribute('class', 'form-horizontal');
		$this->setAttribute('enctype','multipart/form-data');

		$this->clients = $clients;
		$this->paymentMethods = $paymentMethods;
		$this->sellers = $sellers;

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
				'label' => 'register_date',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),

				),
			));

		$this->add(array(
			'type' => 'select',
			'name' => 'client',
			'options' => array(
				'label' => 'client',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
				'value_options' => $this->clients,
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
			'name' => 'seller',
			'options' => array(
				'label' => 'seller',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
				'value_options' => $this->sellers,
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