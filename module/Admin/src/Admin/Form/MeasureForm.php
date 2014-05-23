<?php
namespace Admin\Form;

use Admin\Model\Measure;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Form\Element\Select as select;
use Admin\Model\MeasureMasterTable;

class MeasureForm extends Form
{
	private $specificationlist;
	private $measureTypeList;

	public function __construct($specificationlist, $measureTypeList)
	{
		parent::__construct('measure');

		$this->setAttribute('method', 'post');
		$this->setAttribute('class', 'form-horizontal');
		$this->setAttribute('enctype','multipart/form-data');
		
		$this->specificationlist = $specificationlist;
		$this->measureTypeList = $measureTypeList;

		$this->add(array(
			'name' => 'id',
			'attributes' => array(
				'type'  => 'hidden',
				),
			));

		$this->add(array(
			'type' => 'select',
			'name' => 'specification',
			'options' => array(
				'label' => 'specification',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
				'value_options' => $this->specificationlist,
				'empty_option' => 'seleccione una opción',
				'disable_inarray_validator' => true,
				),
			'attributes' => array(
				'class' => 'form-control',
				)
			));

		$this->add(array(
			'type' => 'select',
			'name' => 'measure_type',
			'options' => array(
				'label' => 'measure_type',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
				'value_options' => $this->measureTypeList,
				'empty_option' => 'sin medida',
				'disable_inarray_validator' => true,
				),
			'attributes' => array(
				'class' => 'form-control',
				)
			));


		$this->add(array(
			'name' => 'measure_value',
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