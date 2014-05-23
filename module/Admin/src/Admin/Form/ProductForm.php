<?php
namespace Admin\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class ProductForm extends Form
{
	private $appsList;
	public function __construct($brand,$category,$appsList)
	{
		parent::__construct('product');
		$this->setAttribute('method', 'post');
		$this->setAttribute('class', 'form-horizontal');
		$this->setAttribute('enctype','multipart/form-data');

		$this->setAppsList($appsList);

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
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
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
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
				),
			));

		$this->add(array(
			'type' => 'select',
			'name' => 'brand',
			'options' => array(
				'label' => 'brand',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
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
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
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
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
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
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
				),
			));
		$this->add(array(
			'name' => 'iva',
			'type' => 'Checkbox',
			'options' => array(
				'use_hidden_element' => true,
				'checked_value' => 1,
				'unchecked_value' => '',
				'label' => 'Iva',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
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
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),						
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
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),						
				),
			));

		$this->add(array(
			'name' => 'specification_file',
			'attributes' => array(
				'type'  => 'file',
				),
			'options' => array(
				'label' => 'specification_file',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
				),
			)); 

		$this->add(array(
			'name' => 'image1',
			'attributes' => array(
				'type'  => 'file',
				),
			'options' => array(
				'label' => 'image1',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
				),
			)); 

		$this->add(array(
			'name' => 'image2',
			'attributes' => array(
				'type'  => 'file',
				),
			'options' => array(
				'label' => 'image2',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
				),
			)); 

		$this->add(array(
			'name' => 'image3',
			'attributes' => array(
				'type'  => 'file',
				),
			'options' => array(
				'label' => 'image3',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
				),
			)); 

		$this->add(array(
			'name' => 'image4',
			'attributes' => array(
				'type'  => 'file',
				),
			'options' => array(
				'label' => 'image4',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
				),
			)); 

		$this->add(array(
			'name' => 'image5',
			'attributes' => array(
				'type'  => 'file',
				),
			'options' => array(
				'label' => 'image5',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
				),
			)); 

		$this->add(array(
			'name' => 'image6',
			'attributes' => array(
				'type'  => 'file',
				),
			'options' => array(
				'label' => 'image6',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
				),
			)); 

		$this->add(array(
			'name' => 'manual_file',
			'attributes' => array(
				'type'  => 'file',
				),
			'options' => array(
				'label' => 'manual_file',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
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
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
				),
			));

		$this->add(array(
			'type' => 'select',
			'name' => 'status',
			'options' => array(
				'label' => 'status',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
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
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
				),
			));

		$this->add(array(
			'type' => 'select',
			'name' => 'apps',
			'attributes' => array(
				'multiple' => 'multiple',
				'class' => 'form-control',
				),
			'options' => array(
				'label' => 'apps',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
				'value_options' => $this->getAppsList(),
				'empty_option' => 'seleccione una opción',
				'disable_inarray_validator' => true
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


	public function getAppsList()
	{
		return $this->appsList;
	}

	public function setAppsList($appsList)
	{
		$this->appsList = $appsList;
		return $this;
	}
}