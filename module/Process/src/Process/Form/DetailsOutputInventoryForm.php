<?php
namespace Process\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class DetailsOutputInventoryForm extends Form
{
	private $products;
	
	public function __construct()
	{
		parent::__construct('details_output_inventory');
		$this->setAttribute('method', 'post');
		$this->setAttribute('class', 'form-horizontal');
		$this->setAttribute('enctype','multipart/form-data');


		$this->add(array(
			'name' => 'qty',
			'attributes' => array(
				'id'	=> 'qty',
				'type'  => 'text',
				'class' => 'form-control',
				'maxlength'=>'2'
				),
			'options' => array(
				'label' => 'name',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),

				),
			));

		$this->add(array(
			'name' => 'product_serial',
			'attributes' => array(
				'id'	=> 'product_serial',
				'type'  => 'text',
				'class' => 'form-control',
				'placeholder' => 'Ingrese el serial del producto'
				),
			'options' => array(
				'label' => 'name',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),

				),
			));

		$this->add(array(
			'name' => 'cost',
			'attributes' => array(
				'id'	=> 'cost',
				'type'  => 'text',
				'class' => 'form-control number_format',
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
			'name' => 'iva',
			'options' => array(
				'label' => 'iva',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
				'value_options' =>array("1" => "Iva incluido","2" => "Iva excluido","3" => "Excento de iva"),
				'empty_option' => 'seleccione una opción',
				'disable_inarray_validator' => true,
				),
			'attributes' => array(
				'id' => 'iva',
				'class' => 'form-control',
				'data-live-search' => 'true'
				)
			));

		$this->add(array(
			'type' => 'select',
			'name' => 'product',
			'options' => array(
				'label' => 'product',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
				'empty_option' => 'seleccione una opción',
				'disable_inarray_validator' => true,
				),
			'attributes' => array(
				'id' => 'product',
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