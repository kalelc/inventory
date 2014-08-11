<?php
namespace Process\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class DetailsReceiveInventoryForm extends Form
{
	private $products;
	
	public function __construct($products)
	{
		parent::__construct('details_receive_inventory');
		$this->setAttribute('method', 'post');
		$this->setAttribute('class', 'form-horizontal');
		$this->setAttribute('enctype','multipart/form-data');

		$this->products = $products;
		


		$this->add(array(
			'name' => 'id',
			'attributes' => array(
				'type'  => 'hidden',
				),
			));

		$this->add(array(
			'name' => 'qty',
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
			'type' => 'select',
			'name' => 'product',
			'options' => array(
				'label' => 'product',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
				'value_options' => $this->products,
				'empty_option' => 'seleccione una opción',
				'disable_inarray_validator' => true,
				),
			'attributes' => array(
				'class' => 'form-control',
				'data-live-search' => 'true'
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