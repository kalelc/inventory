<?php
namespace Admin\Form;

use Admin\Model\Classification;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Form\Element\Select as select;

class ClassificationForm extends Form
{

	public function __construct($userTypeList)
	{
		parent::__construct('classification');

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
			'type' => 'select',
			'name' => 'user_type',
			'attributes' => array(
				'class' => 'form-control',
				),
			'options' => array(
				'label' => 'user_type',
				'label_attributes' => array(
					'class'  => 'col-sm-2 control-label'
					),
				'value_options' => $userTypeList,
				'empty_option' => '',
				'disable_inarray_validator' => true,
				)
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