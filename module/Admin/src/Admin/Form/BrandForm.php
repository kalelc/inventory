<?php
namespace Admin\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Admin\Model\BrandTable;

class BrandForm extends Form
{

	public function __construct()
	{
		parent::__construct('brand');
		
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
			'name' => 'name',
			'attributes' => array(
				'type'  => 'text',
				'class' => 'form-control',
				),
			'options' => array(
				'label' => 'nombre',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),

				),
			));
		
		$this->add(array(
			'name' => 'image',
			'attributes' => array(
				'type'  => 'file',
				),
			'options' => array(
				'label' => 'imagen',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
				),
			)); 

		$this->add(array(
			'name' => 'background_image',
			'attributes' => array(
				'type'  => 'file',
				),
			'options' => array(
				'label' => 'background_imagen',
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
				'label' => 'descripción',
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