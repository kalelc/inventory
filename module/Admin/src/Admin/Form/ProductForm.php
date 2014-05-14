<?php
namespace Admin\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class ProductForm extends Form
{
	public function __construct($brand,$category)
	{
		parent::__construct('product');
		$this->setAttribute('method', 'post');
		$this->setAttribute('class', 'form-horizontal');
		$this->setAttribute('enctype','multipart/form-data');

		$this->add(array(
			'name' => 'id',
			'attributes' => array(
				'type'  => 'hidden',
				),
			));
		
		$this->add(array(
			'name' => 'upc_bar_code',
			'attributes' => array(
				'type'  => 'text',
				'class' => 'form-control',
				),
			'options' => array(
				'label' => 'upc_bar_code',
				),
			));

		$this->add(array(
			'name' => 'measures',
			'attributes' => array(
				'type'  => 'select',
				),
			));

		$this->add(array(
			'name' => 'model',
			'attributes' => array(
				'type'  => 'text',
				'class' => 'form-control',
				),
			'options' => array(
				'label' => 'model',
				),
			));

		$this->add(array(
			'type' => 'select',
			'name' => 'brand',
			'options' => array(
				'label' => 'brand',
				'value_options' => $brand,
				'empty_option' => 'seleccione una opción',
				'disable_inarray_validator' => true,
				),
			'attributes' => array(
				'class' => 'form-control',
				)
			));

		$this->add(array(
			'type' => 'select',
			'name' => 'category',
			'options' => array(
				'label' => 'category',
				'value_options' => $category,
				'empty_option' => 'seleccione una opción',
				'disable_inarray_validator' => true,
				),
			'attributes' => array(
				'class' => 'form-control',
				'id' => 'category',
				)
			));

		$this->add(array(
			'name' => 'part_no',
			'attributes' => array(
				'type'  => 'text',
				'class' => 'form-control',
				),
			'options' => array(
				'label' => 'name',
				),
			));

		$this->add(array(
			'name' => 'price',
			'attributes' => array(
				'type'  => 'text',
				'class' => 'form-control number-format',
				),
			'options' => array(
				'label' => 'price',
				),
			));
		$this->add(array(
			'name' => 'iva',
			'type' => 'Checkbox',
			'options' => array(
				'use_hidden_element' => true,
				'checked_value' => 1,
				'unchecked_value' => '',
				'label' => 'Iva'
				),
			'attributes' => array(
				'id' => 'iva'
				)
			));
		$this->add(array(
			'name' => 'qty_low',
			'attributes' => array(
				'type'  => 'text',
				'class' => 'form-control',
				),
			'options' => array(
				'label' => 'qty_low',						
				),
			));

		$this->add(array(
			'name' => 'qty_buy',
			'attributes' => array(
				'type'  => 'text',
				'class' => 'form-control',
				),
			'options' => array(
				'label' => 'qty_buy',						
				),
			));

		$this->add(array(
			'name' => 'specification_file',
			'attributes' => array(
				'type'  => 'file',
				),
			'options' => array(
				'label' => 'specification_file',
				),
			)); 

		$this->add(array(
			'name' => 'image1',
			'attributes' => array(
				'type'  => 'file',
				),
			'options' => array(
				'label' => 'image1',
				),
			)); 

		$this->add(array(
			'name' => 'image2',
			'attributes' => array(
				'type'  => 'file',
				),
			'options' => array(
				'label' => 'image2',
				),
			)); 

		$this->add(array(
			'name' => 'image3',
			'attributes' => array(
				'type'  => 'file',
				),
			'options' => array(
				'label' => 'image3',
				),
			)); 

		$this->add(array(
			'name' => 'image4',
			'attributes' => array(
				'type'  => 'file',
				),
			'options' => array(
				'label' => 'image4',
				),
			)); 

		$this->add(array(
			'name' => 'image5',
			'attributes' => array(
				'type'  => 'file',
				),
			'options' => array(
				'label' => 'image5',
				),
			)); 

		$this->add(array(
			'name' => 'image6',
			'attributes' => array(
				'type'  => 'file',
				),
			'options' => array(
				'label' => 'image6',
				),
			)); 

		$this->add(array(
			'name' => 'manual_file',
			'attributes' => array(
				'type'  => 'file',
				),
			'options' => array(
				'label' => 'manual_file',
				),
			));

		$this->add(array(
			'name' => 'video',
			'attributes' => array(
				'type'  => 'text',
				'class' => 'form-control',
				),
			'options' => array(
				'label' => 'video',
				),
			));

		$this->add(array(
			'type' => 'select',
			'name' => 'status',
			'options' => array(
				'label' => 'status',
				'value_options' => array(1 => "Activo",2 => "Inactivo"),
				),
			'attributes' => array(
				'class' => 'form-control',
				)

			));

		$this->add(array(
			'name' => 'description',
			'attributes' => array(
				'type'  => 'textarea',
				'class' => 'form-control',
				),
			'options' => array(
				'label' => 'descripción',
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
				'class' => 'btn btn-primary btn-sm btn-sm'
				),
			));
	}
}