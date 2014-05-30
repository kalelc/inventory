<?php
namespace Admin\Form;

use Admin\Model\Specification;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Form\Element\Select as select;
use Admin\Model\SpecificationMasterTable;

class SpecificationForm extends Form
{
	private $specificationMasterList;

	public function __construct($specificationList)
	{
		parent::__construct('specification');

		$this->setAttribute('method', 'post');
		$this->setAttribute('class', 'form-horizontal');
		$this->setAttribute('enctype','multipart/form-data');
		
		$this->specificationMasterList = $specificationList;
		
		
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
			'type' => 'select',
			'name' => 'specification_master',
			'attributes' => array(
				'class' => 'form-control',
				),
			'options' => array(
				'label' => 'specification_master',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
				'value_options' => $this->specificationMasterList,
				'empty_option' => 'seleccione una opción',
				'disable_inarray_validator' => true,
				)
			));


		$this->add(array(
			'name' => 'image',
			'attributes' => array(
				'type'  => 'file',
				),
			'options' => array(
				'label' => 'image',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
				),
			));

		$this->add(array(
			'name' => 'meaning',
			'attributes' => array(
				'type'  => 'textarea',
				'class' => 'form-control',
				),
			'options' => array(
				'label' => 'meaning',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
				),
			));

		$this->add(array(
			'name' => 'general_information',
			'attributes' => array(
				'type'  => 'textarea',
				'class' => 'form-control',
				),
			'options' => array(
				'label' => 'general_information',
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